<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAssistantController extends Controller
{
    public function chat(Request $request)
    {
        $messages = $request->input('messages', []);
        $reply = $this->callGemini($messages, $this->chatSystemPrompt());
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

        $reply = $this->callGemini(
            [['role' => 'user', 'content' => $userMessage]],
            $this->recommendSystemPrompt()
        );

        return response()->json(['reply' => $reply]);
    }

    private function callGemini(array $messages, string $systemPrompt): string
    {
        $apiKey = config('app.gemini_api_key') ?: env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            return 'Konfigurasi API belum lengkap. Hubungi administrator.';
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        $contents = [];
        foreach ($messages as $msg) {
            $role = $msg['role'] === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role'  => $role,
                'parts' => [['text' => $msg['content']]]
            ];
        }

        if (empty($contents)) {
            $contents[] = [
                'role'  => 'user',
                'parts' => [['text' => 'halo']]
            ];
        }

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]]
            ],
            'contents'         => $contents,
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 1024,
            ],
        ];

        try {
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->post($url, $payload);

            if ($response->failed()) {
                $status = $response->status();
                \Log::error('Gemini HTTP Error: ' . $status . ' - ' . $response->body());

                // ⭐ Handle berbagai error code
                if ($status === 429) {
                    return 'AI sedang sibuk, silakan tunggu sebentar dan coba lagi. 🙏';
                }
                if ($status === 403) {
                    return 'API Key tidak memiliki akses. Hubungi administrator.';
                }
                if ($status === 400) {
                    return 'Format request tidak valid. Silakan coba lagi.';
                }

                return 'Layanan AI sedang tidak tersedia (HTTP ' . $status . ').';
            }

            $data = $response->json();

            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }

            if (isset($data['error'])) {
                \Log::error('Gemini API Error: ' . json_encode($data['error']));
                return 'Error: ' . ($data['error']['message'] ?? 'Unknown error');
            }

            return 'Maaf, terjadi kesalahan. Silakan coba lagi.';

        } catch (\Exception $e) {
            \Log::error('Gemini Exception: ' . $e->getMessage());
            return 'Koneksi gagal: ' . $e->getMessage();
        }
    }

    private function chatSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah AI Assistant TechStore, toko CCTV dan networking terpercaya di Indonesia.
Tugasmu membantu customer memilih produk CCTV, akses kontrol, dan networking.

Brand yang kami jual: Hikvision, Dahua, HiLook, EZVIZ, UNV, Ruijie, Foreage.

Jawab dalam Bahasa Indonesia, ramah, dan informatif.
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
Jawab dalam Bahasa Indonesia yang ramah dan profesional.
PROMPT;
    }
}