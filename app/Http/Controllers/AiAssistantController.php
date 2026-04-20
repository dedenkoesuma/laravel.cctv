<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAssistantController extends Controller
{
    public function chat(Request $request)
    {
        $messages = $request->input('messages', []);
        $reply = $this->callGroq($messages, $this->chatSystemPrompt());
        return response()->json(['reply' => $reply]);
    }

    public function recommend(Request $request)
    {
        $lokasi    = $request->input('lokasi');
        $budget    = $request->input('budget');
        $jumlahCam = $request->input('jumlah_cam');
        $fitur     = $request->input('fitur_khusus', []);

        $userMessage = "Rekomendasikan produk CCTV untuk:
- Lokasi: {$lokasi}
- Budget: Rp " . number_format($budget, 0, ',', '.') . "
- Jumlah kamera: {$jumlahCam}
- Fitur khusus: " . implode(', ', $fitur);

        $reply = $this->callGroq(
            [['role' => 'user', 'content' => $userMessage]],
            $this->recommendSystemPrompt()
        );

        return response()->json(['reply' => $reply]);
    }

    private function callGroq(array $messages, string $systemPrompt): string
    {
        // Mengambil key Groq dari .env
        $apiKey = env('GROQ_API_KEY');

        if (empty($apiKey)) {
            return 'Konfigurasi API Groq belum lengkap. Hubungi administrator.';
        }

        // Endpoint Groq yang kompatibel dengan format OpenAI
        $url = "https://api.groq.com/openai/v1/chat/completions";

        // Susun messages: System prompt harus selalu di urutan pertama
        $formattedMessages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Masukkan riwayat chat dari user
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'role'  => $msg['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $msg['content']
            ];
        }

        // Pastikan ada pesan jika kosong
        if (count($formattedMessages) === 1) {
            $formattedMessages[] = [
                'role' => 'user',
                'content' => 'halo'
            ];
        }

        $payload = [
            'model'       => 'llama-3.1-8b-instant', // Model Llama 3 gratis dan sangat cepat dari Groq
            'messages'    => $formattedMessages,
            'temperature' => 0.7,
            'max_tokens'  => 1024,
        ];

        try {
            $response = Http::withToken($apiKey) 
                ->timeout(15)
                ->withoutVerifying()
                ->post($url, $payload);

            if ($response->failed()) {
                $status = $response->status();
                \Log::error('Groq HTTP Error: ' . $status . ' - ' . $response->body());

                if ($status === 429) {
                    return 'AI sedang memproses terlalu banyak permintaan. Silakan tunggu sebentar. 🙏';
                }
                if ($status === 401) {
                    return 'API Key Groq tidak valid.';
                }

                return 'Layanan AI sedang tidak tersedia (HTTP ' . $status . ').';
            }

            $data = $response->json();

            // Cara mengambil teks balasan 
            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content'];
            }

            return 'Maaf, format balasan dari AI tidak sesuai.';

        } catch (\Exception $e) {
            \Log::error('Groq Exception: ' . $e->getMessage());
            return 'Koneksi gagal: ' . $e->getMessage();
        }
    }

    private function chatSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah AI Assistant TechStore, toko CCTV dan networking terpercaya di Indonesia.
Tugasmu membantu customer memilih produk CCTV, akses kontrol, dan networking.

Brand yang kami jual: Hikvision, Dahua, HiLook, EZVIZ, UNV, Ruijie, Foreage.

Jawab dalam Bahasa Indonesia, ramah, singkat, dan informatif.
Jika ada pertanyaan harga, selalu sebutkan estimasi harga dalam Rupiah.
PROMPT;
    }

    private function recommendSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah AI specialist CCTV di TechStore Indonesia.
Berikan rekomendasi paket CCTV yang detail berdasarkan kebutuhan customer.

Brand yang tersedia: Hikvision, Dahua, HiLook, EZVIZ, UNV, Foreage.

Berikan 2-3 rekomendasi produk utama beserta estimasi total biaya.
Jawab dalam Bahasa Indonesia yang ramah, ringkas, dan profesional.
PROMPT;
    }
}