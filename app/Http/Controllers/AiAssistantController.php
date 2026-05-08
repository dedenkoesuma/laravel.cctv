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

    // Fungsi "Kebal Error" untuk ambil data katalog
    private function getKatalogText()
    {
        // Ambil data tanpa filter kolom spesifik (menghindari error column not found)
        $products = Product::limit(50)->get();
        $text = "";
        
        foreach ($products as $p) {
            // Cek dinamis: kalau 'nama' nggak ada, pakai 'name', dst.
            $nama  = $p->nama ?? $p->name ?? $p->title ?? 'Produk Tanpa Nama';
            $harga = $p->harga ?? $p->price ?? 0;
            $spek  = $p->spesifikasi ?? $p->description ?? $p->deskripsi ?? '-';
            $brand = $p->brand ?? $p->merk ?? '-';

            $hargaFormat = 'Rp ' . number_format((float)$harga, 0, ',', '.');
            $text .= "- {$nama} ({$brand}) | Harga: {$hargaFormat} | Spek: {$spek}\n";
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

        try {
            $messages = $request->input('messages', []);
            $katalog  = $this->getKatalogText();
            $reply    = $this->callGroq($messages, $this->chatSystemPrompt($katalog));

            return response()->json(['reply' => $reply]);
            
        } catch (\Exception $e) {
            // Jika database error, tampilkan pesannya langsung di chat
            return response()->json([
                'reply' => 'Waduh Den, ada error di Database nih: ' . $e->getMessage()
            ]);
        }
    }

    public function recommend(Request $request)
    {
        $request->validate([
            'lokasi'      => 'required|string',
            'budget'      => 'required|numeric',
            'jumlah_cam'  => 'required|integer|min:1|max:64',
            'fitur_khusus'=> 'nullable|array',
        ]);

        try {
            $lokasi    = $request->input('lokasi');
            $budget    = $request->input('budget');
            $jumlahCam = $request->input('jumlah_cam');
            $fitur     = $request->input('fitur_khusus', []);

            $maxPricePerItem = ($budget / $jumlahCam) * 2;

            // Ambil semua produk, filter manual via Collection agar tidak crash di Query SQL
            $allProducts = Product::all();
            
            $products = $allProducts->filter(function($p) use ($maxPricePerItem) {
                $harga = $p->harga ?? $p->price ?? 0;
                return $harga <= $maxPricePerItem;
            })->take(30);

            // Cegat jika budget kekecilan
            if ($products->isEmpty()) {
                $termurah = $allProducts->sortBy(function($p) {
                    return $p->harga ?? $p->price ?? 0;
                })->first();
                
                if ($termurah) {
                    $namaTermurah  = $termurah->nama ?? $termurah->name ?? 'Paket CCTV';
                    $hargaTermurah = $termurah->harga ?? $termurah->price ?? 0;
                    $hargaMin      = 'Rp ' . number_format((float)$hargaTermurah, 0, ',', '.');
                    
                    return response()->json([
                        'reply' => "Mohon maaf, untuk budget tersebut kami belum memiliki paket yang sesuai. Paket termurah kami saat ini adalah **{$namaTermurah}** dengan harga **{$hargaMin}**.\n\nApakah Anda ingin mempertimbangkan paket tersebut?"
                    ]);
                }
                
                return response()->json(['reply' => 'Maaf, saat ini stok produk kami sedang kosong.']);
            }

            $katalogText = "";
            foreach ($products as $p) {
                $nama  = $p->nama ?? $p->name ?? 'Produk';
                $harga = $p->harga ?? $p->price ?? 0;
                $spek  = $p->spesifikasi ?? $p->description ?? '-';
                $brand = $p->brand ?? $p->merk ?? '-';
                
                $hargaFormat = 'Rp ' . number_format((float)$harga, 0, ',', '.');
                $katalogText .= "- {$nama} ({$brand}) | Harga: {$hargaFormat} | Spek: {$spek}\n";
            }

            $fiturText    = !empty($fitur) ? implode(', ', $fitur) : 'Tidak ada';
            $budgetFormat = 'Rp ' . number_format($budget, 0, ',', '.');

            $userMessage = "Rekomendasikan produk CCTV untuk lokasi: {$lokasi}, budget: {$budgetFormat}, jumlah kamera: {$jumlahCam}, fitur: {$fiturText}.";

            $reply = $this->callGroq(
                [['role' => 'user', 'content' => $userMessage]],
                $this->recommendSystemPrompt($katalogText)
            );

            return response()->json(['reply' => $reply]);
            
        } catch (\Exception $e) {
            return response()->json([
                'reply' => 'Gagal baca database rekomendasi. Error: ' . $e->getMessage()
            ]);
        }
    }

    private function callGroq(array $messages, string $systemPrompt): string
    {
        $apiKey = config('services.groq.key');

        if (empty($apiKey)) {
            return 'Konfigurasi API Key Groq belum lengkap.';
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
                'temperature' => 0.5, 
                'max_tokens'  => 1024,
                'stream'      => false,
            ]);

            if ($response->failed()) {
                return "Maaf, AI sedang mengalami gangguan jaringan dengan Groq.";
            }

            return $response->json()['choices'][0]['message']['content'] ?? 'Maaf, format balasan error.';
        } catch (\Exception $e) {
            return 'Terjadi kesalahan sistem API: ' . $e->getMessage();
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