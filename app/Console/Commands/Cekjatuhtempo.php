<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CekJatuhTempo extends Command
{
    protected $signature   = 'finance:cek-jatuh-tempo';
    protected $description = 'Cek piutang yang mendekati atau melewati jatuh tempo, simpan notifikasi in-app';

    public function handle(): void
    {
        $today = Carbon::today()->startOfDay();

        $invoices = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')
            ->where('status', 'pending')
            ->whereNotNull('jatuh_tempo')
            ->get();

        $this->info("Memproses {$invoices->count()} invoice...");

        $kirim = 0;

        foreach ($invoices as $inv) {
            $jatuhTempo = Carbon::parse($inv->jatuh_tempo)->startOfDay();
            $selisih    = $today->diffInDays($jatuhTempo, false);

            if ($selisih === 3 && !$inv->notif_h3_sent) {
                $this->buatNotif('h3', $inv);
                DB::table('keuangan_transaksi')->where('id', $inv->id)->update(['notif_h3_sent' => true]);
                $kirim++;
            }

            if ($selisih === 1 && !$inv->notif_h1_sent) {
                $this->buatNotif('h1', $inv);
                DB::table('keuangan_transaksi')->where('id', $inv->id)->update(['notif_h1_sent' => true]);
                $kirim++;
            }

            if ($selisih < 0 && !$inv->notif_overdue_sent) {
                $this->buatNotif('overdue', $inv);
                DB::table('keuangan_transaksi')->where('id', $inv->id)->update(['notif_overdue_sent' => true]);
                $kirim++;
            }
        }

        $this->info("Selesai. {$kirim} notifikasi dibuat.");
        Log::info("finance:cek-jatuh-tempo — {$kirim} notifikasi dibuat.", ['tanggal' => $today]);
    }

    private function buatNotif(string $tipe, object $inv): void
    {
        $customer = $inv->pihak_terkait ?? '-';
        $total    = 'Rp ' . number_format($inv->jumlah, 0, ',', '.');
        $tgl      = Carbon::parse($inv->jatuh_tempo)->format('d/m/Y');
        $kode     = $inv->kode_transaksi ?? $inv->invoice_number ?? '-';

        $template = [
            'h3' => [
                'judul' => "Jatuh tempo 3 hari lagi — {$kode}",
                'pesan' => "Piutang {$kode} atas nama {$customer} senilai {$total} akan jatuh tempo pada {$tgl}.",
            ],
            'h1' => [
                'judul' => "Jatuh tempo besok! — {$kode}",
                'pesan' => "Piutang {$kode} atas nama {$customer} senilai {$total} jatuh tempo besok ({$tgl}). Segera follow up.",
            ],
            'overdue' => [
                'judul' => "Piutang overdue — {$kode}",
                'pesan' => "Piutang {$kode} atas nama {$customer} senilai {$total} telah melewati jatuh tempo ({$tgl}).",
            ],
        ];

        Notification::create([
            'tipe'       => $tipe,
            'judul'      => $template[$tipe]['judul'],
            'pesan'      => $template[$tipe]['pesan'],
            'invoice_id' => null, // ✅ null — piutang dari keuangan_transaksi, bukan tabel invoices
        ]);
    }
}