<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product; 

class AiAssistantController extends Controller
{
    private string $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private string $model   = 'llama-3.3-70b-versatile'; 

    // Bikin fungsi khusus untuk narik semua katalog buat chatbot biasa
    private function getKatalogText()
    {
        // Ambil maksimal 50 produk untuk konteks chat biasa
        $products = Product::where('stok', '>', 0)->limit(50)->get(['brand', 'nama', 'harga', 'spesifikasi']);
        $text = "";
        foreach ($products as $p) {
            $hargaFormat = 'Rp ' . number_format($p->harga, 0, ',', '.');
            $text .= "- {$p->nama} ({$p->brand}) | Harga: {$hargaFormat} | Spek: {$p->spesifikasi}\n";
        }
        return $text;
    }
    
    public function chat(Request $request)
    {
        $request->validate([
            'messages'          => 'required|array|min:1',
            'messages.*.role'   => 'required|in:user,assistant',
            'messages.*.content'=> 'required|string|max:4000',
        ]);

        $messages = $request->input('messages', []);
        
        // Masukkan data produk ke dalam prompt chat
        $katalog = $this->getKatalogText();
        $reply   = $this->callGroq($messages, $this->chatSystemPrompt($katalog));

        return response()->json(['reply' => $reply]);
    }

    public function recommend(Request $request)
    {
        $request->validate([
            'lokasi'      => 'required|string',
            'budget'      => 'required|numeric',
            'jumlah_cam'  => 'required|integer|min:1|max:64',
            'fitur_khusus'=> 'nullable|array',
        ]);

        $lokasi    = $request->input('lokasi');
        $budget    = $request->input('budget');
        $jumlahCam = $request->input('jumlah_cam');
        $fitur     = $request->input('fitur_khusus', []);

        // Filter harga
        $maxPricePerItem = ($budget / $jumlahCam) * 2;
        $products = Product::where('harga', '<=', $maxPricePerItem)
            ->where('stok', '>', 0)
            ->limit(30)
            ->get(['brand', 'nama', 'harga', 'spesifikasi']);

        // CEGAT DISINI JIKA BUDGET KEKECILAN (TIDAK ADA PRODUK)
        if ($products->isEmpty()) {
            // Cari produk paling murah di database untuk diinfokan ke user
            $termurah = Product::orderBy('harga', 'asc')->first();
            
            if ($termurah) {
                $hargaMin = 'Rp ' . number_format($termurah->harga, 0, ',', '.');
                return response()->json([
                    'reply' => "Mohon maaf, untuk budget tersebut kami belum memiliki paket yang sesuai. Paket termurah kami saat ini adalah **{$termurah->nama}** dengan harga **{$hargaMin}**.\n\nApakah Anda ingin mempertimbangkan paket tersebut?"
                ]);
            }
            
            return response()->json(['reply' => 'Maaf, saat ini stok produk kami sedang kosong.']);
        }

        $katalogText = "";
        foreach ($products as $p) {
            $hargaFormat = 'Rp ' . number_format($p->harga, 0, ',', '.');
            $katalogText .= "- {$p->brand} {$p->nama} | Harga: {$hargaFormat} | Spek: {$p->spesifikasi}\n";
        }

        $fiturText   = !empty($fitur) ? implode(', ', $fitur) : 'Tidak ada';
        $budgetFormat = 'Rp ' . number_format($budget, 0, ',', '.');

        $userMessage = "Rekomendasikan produk CCTV untuk lokasi: {$lokasi}, budget: {$budgetFormat}, jumlah kamera: {$jumlahCam}, fitur: {$fiturText}.";

        $reply = $this->callGroq(
            [['role' => 'user', 'content' => $userMessage]],
            $this->recommendSystemPrompt($katalogText)
        );

        return response()->json(['reply' => $reply]);
    }

    private function callGroq(array $messages, string $systemPrompt): string
    {
        $apiKey = config('services.groq.key');

        if (empty($apiKey)) {
            return 'Konfigurasi AI belum lengkap. Hubungi administrator. 🙏';
        }

        $formattedMessages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'role'    => ($msg['role'] === 'assistant') ? 'assistant' : 'user',
                'content' => trim($msg['content']),
            ];
        }

        try {
            $response = Http::withToken($apiKey)->timeout(20)->retry(2, 500)->post($this->groqUrl, [
                'model'       => $this->model,
                'messages'    => $formattedMessages,
                'temperature' => 0.5, // Turunkan sedikit biar gak ngarang
                'max_tokens'  => 1024,
                'stream'      => false,
            ]);

            if ($response->failed()) {
                return "Maaf, AI sedang mengalami gangguan jaringan.";
            }

            return $response->json()['choices'][0]['message']['content'] ?? 'Maaf, format balasan error.';
        } catch (\Exception $e) {
            return 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }

    private function chatSystemPrompt(string $katalog = ""): string
    {
        return <<<PROMPT
Kamu adalah AI Assistant TechStore, toko CCTV di Indonesia.

ATURAN MUTLAK:
1. Jika ditanya soal produk atau harga, KAMU WAJIB HANYA MENGGUNAKAN data dari daftar KATALOG di bawah ini.
2. JANGAN PERNAH menyebutkan harga atau produk yang tidak ada di dalam KATALOG.
3. Jika produk yang dicari tidak ada di KATALOG, katakan: "Maaf, kami belum memiliki produk tersebut."

=== KATALOG PRODUK TECHSTORE ===
{$katalog}
================================

Jawablah dengan ramah dan informatif.
PROMPT;
    }

    private function recommendSystemPrompt(string $katalogText): string
    {
        return <<<PROMPT
Kamu adalah AI specialist CCTV di TechStore Indonesia.

ATURAN MUTLAK:
1. KAMU WAJIB HANYA merekomendasikan produk dari "Katalog Produk TechStore" di bawah ini.
2. DILARANG KERAS merekomendasikan produk atau mengarang harga di luar daftar katalog.

=== KATALOG PRODUK TECHSTORE ===
{$katalogText}
================================

Berikan maksimal 2 opsi paket. Cantumkan nama, harga persis sesuai katalog, dan alasan singkat kenapa cocok.
PROMPT;
    }
}