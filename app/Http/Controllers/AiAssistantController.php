<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantController extends Controller
{
    private string $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private string $model   = 'llama-3.3-70b-versatile'; // Model terbaru & gratis di Groq
    
    public function chat(Request $request)
    {
        $request->validate([
            'messages'          => 'required|array|min:1',
            'messages.*.role'   => 'required|in:user,assistant',
            'messages.*.content'=> 'required|string|max:4000',
        ]);

        $messages = $request->input('messages', []);
        $reply    = $this->callGroq($messages, $this->chatSystemPrompt());

        return response()->json(['reply' => $reply]);
    }

    public function recommend(Request $request)
    {
        $request->validate([
            'lokasi'      => 'required|string',
            'budget'      => 'required|numeric|min:500000',
            'jumlah_cam'  => 'required|integer|min:1|max:64',
            'fitur_khusus'=> 'nullable|array',
        ]);

        $lokasi    = $request->input('lokasi');
        $budget    = $request->input('budget');
        $jumlahCam = $request->input('jumlah_cam');
        $fitur     = $request->input('fitur_khusus', []);

        $fiturText   = !empty($fitur) ? implode(', ', $fitur) : 'Tidak ada';
        $budgetFormat = 'Rp ' . number_format($budget, 0, ',', '.');

        $userMessage = <<<MSG
Rekomendasikan produk CCTV untuk kebutuhan berikut:
- Lokasi        : {$lokasi}
- Budget        : {$budgetFormat}
- Jumlah kamera : {$jumlahCam} unit
- Fitur khusus  : {$fiturText}
MSG;

        $reply = $this->callGroq(
            [['role' => 'user', 'content' => $userMessage]],
            $this->recommendSystemPrompt()
        );

        return response()->json(['reply' => $reply]);
    }

    private function callGroq(array $messages, string $systemPrompt): string
    {
        $apiKey = config('services.groq.key'); // Ambil dari config, bukan env() langsung

        if (empty($apiKey)) {
            Log::error('GROQ_API_KEY tidak ditemukan di konfigurasi.');
            return 'Konfigurasi AI belum lengkap. Hubungi administrator. 🙏';
        }

        // Susun messages: system prompt selalu di posisi pertama
        $formattedMessages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($messages as $msg) {
            $role = ($msg['role'] === 'assistant') ? 'assistant' : 'user';
            $formattedMessages[] = [
                'role'    => $role,
                'content' => trim($msg['content']),
            ];
        }

        $payload = [
            'model'       => $this->model,
            'messages'    => $formattedMessages,
            'temperature' => 0.7,
            'max_tokens'  => 1024,
            'stream'      => false,
        ];

        try {
            $response = Http::withToken($apiKey)
                ->timeout(20)
                ->retry(2, 500) // Otomatis retry 2x jika gagal
                ->post($this->groqUrl, $payload);

            if ($response->failed()) {
                return $this->handleGroqError($response->status(), $response->body());
            }

            $data = $response->json();

            return $data['choices'][0]['message']['content']
                ?? 'Maaf, format balasan dari AI tidak sesuai.';

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Groq Connection Error: ' . $e->getMessage());
            return 'Koneksi ke layanan AI gagal. Periksa koneksi internet server. 🔌';
        } catch (\Exception $e) {
            Log::error('Groq Exception: ' . $e->getMessage());
            return 'Terjadi kesalahan tak terduga: ' . $e->getMessage();
        }
    }

    private function handleGroqError(int $status, string $body): string
    {
        Log::error("Groq HTTP Error [{$status}]: {$body}");

        return match ($status) {
            400 => 'Permintaan tidak valid. Coba ulangi dengan pertanyaan berbeda.',
            401 => 'API Key Groq tidak valid atau sudah kedaluwarsa. Hubungi administrator. 🔑',
            429 => 'AI sedang sibuk (rate limit). Tunggu beberapa detik lalu coba lagi. ⏳',
            500, 503 => 'Server Groq sedang gangguan. Coba lagi nanti. 🛠️',
            default => "Layanan AI tidak tersedia saat ini (HTTP {$status}).",
        };
    }

    private function chatSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah AI Assistant TechStore, toko CCTV dan networking terpercaya di Indonesia.
Tugasmu membantu customer memilih produk CCTV, akses kontrol, dan networking.

Brand yang kami jual: Hikvision, Dahua, HiLook, EZVIZ, UNV, Ruijie, Foreage.

Aturan:
- Jawab selalu dalam Bahasa Indonesia yang ramah, singkat, dan informatif.
- Jika ditanya harga, sebutkan estimasi dalam Rupiah.
- Jika pertanyaan di luar topik CCTV/networking, tolak dengan sopan.
PROMPT;
    }

    private function recommendSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah AI specialist CCTV di TechStore Indonesia.
Tugasmu memberikan rekomendasi paket CCTV yang detail dan sesuai kebutuhan customer.

Brand yang tersedia: Hikvision, Dahua, HiLook, EZVIZ, UNV, Foreage.

Format rekomendasi:
1. Berikan 2-3 opsi paket (Budget / Standar / Premium).
2. Setiap opsi wajib mencantumkan: nama produk, spesifikasi singkat, dan estimasi harga.
3. Tambahkan total estimasi biaya di akhir setiap opsi.
4. Jawab dalam Bahasa Indonesia yang ramah dan profesional.
PROMPT;
    }
}