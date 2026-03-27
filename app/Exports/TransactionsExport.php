<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $startDate;
    protected $endDate;
    
    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate ?? date('Y-m-01');
        $this->endDate = $endDate ?? date('Y-m-t');
    }
    
    /**
     * Get collection of transactions
     */
    public function collection()
    {
        return Transaction::dateRange($this->startDate, $this->endDate)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }
    
    /**
     * Define headings
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'No. Invoice',
            'Produk',
            'Customer',
            'No. Telepon',
            'Qty',
            'Modal/Unit',
            'Jual/Unit',
            'Total Modal',
            'Total Jual',
            'Ongkir',
            'Biaya Tambahan',
            'Total Biaya',
            'Keuntungan',
            'Status',
            'Catatan',
        ];
    }
    
    /**
     * Map data for each row
     */
    public function map($transaction): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->invoice_number,
            $transaction->product_name,
            $transaction->customer_name,
            $transaction->customer_phone ?? '-',
            $transaction->quantity,
            $transaction->modal_per_unit,
            $transaction->selling_price_per_unit,
            $transaction->total_modal,
            $transaction->total_selling,
            $transaction->shipping_cost,
            $transaction->additional_cost,
            $transaction->total_cost,
            $transaction->profit,
            $this->getStatusLabel($transaction->payment_status),
            $transaction->notes ?? '-',
        ];
    }
    
    /**
     * Get status label
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'paid' => 'Lunas',
            'pending' => 'Pending',
            'cancelled' => 'Dibatalkan',
        ];
        
        return $labels[$status] ?? $status;
    }
    
    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (header)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '667eea'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
    
    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 12,  // Tanggal
            'C' => 18,  // Invoice
            'D' => 30,  // Produk
            'E' => 20,  // Customer
            'F' => 15,  // Telepon
            'G' => 6,   // Qty
            'H' => 15,  // Modal/Unit
            'I' => 15,  // Jual/Unit
            'J' => 15,  // Total Modal
            'K' => 15,  // Total Jual
            'L' => 12,  // Ongkir
            'M' => 12,  // Biaya Tambahan
            'N' => 15,  // Total Biaya
            'O' => 15,  // Keuntungan
            'P' => 12,  // Status
            'Q' => 30,  // Catatan
        ];
    }
    
    /**
     * Set sheet title
     */
    public function title(): string
    {
        return 'Transaksi ' . date('d-m-Y', strtotime($this->startDate)) . ' - ' . date('d-m-Y', strtotime($this->endDate));
    }
}