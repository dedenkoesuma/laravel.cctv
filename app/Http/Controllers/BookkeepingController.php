<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ⭐ COMMENTED OUT - Uncomment when packages are installed
// use App\Exports\TransactionsExport;
// use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\Facade\Pdf;

class BookkeepingController extends Controller
{
    /**
     * Display bookkeeping dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        
        // Get summary statistics
        $statistics = $this->getStatistics($startDate, $endDate);
        
        // Get recent transactions
        $transactions = Transaction::dateRange($startDate, $endDate)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);
        
        // Get monthly chart data
        $monthlyData = $this->getMonthlyData();
        
        return view('admin.bookkeeping.index', compact(
            'statistics',
            'transactions',
            'monthlyData',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics($startDate, $endDate)
    {
        $transactions = Transaction::dateRange($startDate, $endDate)
            ->where('payment_status', 'paid')
            ->get();
        
        return [
            'total_transactions' => $transactions->count(),
            'total_modal' => $transactions->sum('total_modal'),
            'total_selling' => $transactions->sum('total_selling'),
            'total_shipping' => $transactions->sum('shipping_cost'),
            'total_additional_cost' => $transactions->sum('additional_cost'),
            'total_cost' => $transactions->sum('total_cost'),
            'total_profit' => $transactions->sum('profit'),
            'pending_count' => Transaction::dateRange($startDate, $endDate)
                ->where('payment_status', 'pending')
                ->count(),
        ];
    }

    /**
     * Get monthly profit data for chart
     */
    private function getMonthlyData()
    {
        $currentYear = date('Y');
        
        $monthlyProfit = Transaction::selectRaw('
                MONTH(transaction_date) as month,
                SUM(profit) as total_profit,
                SUM(total_selling) as total_selling,
                SUM(total_modal) as total_modal
            ')
            ->whereYear('transaction_date', $currentYear)
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        // Fill all 12 months
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'profit' => $monthlyProfit->get($i)->total_profit ?? 0,
                'selling' => $monthlyProfit->get($i)->total_selling ?? 0,
                'modal' => $monthlyProfit->get($i)->total_modal ?? 0,
            ];
        }
        
        return $data;
    }

    /**
     * Show form to create new transaction
     */
    public function create()
    {
        $invoiceNumber = Transaction::generateInvoiceNumber();
        return view('admin.bookkeeping.create', compact('invoiceNumber'));
    }

    /**
     * Store new transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'invoice_number' => 'required|unique:transactions,invoice_number',
            'product_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'quantity' => 'required|integer|min:1',
            'modal_per_unit' => 'required|numeric|min:0',
            'selling_price_per_unit' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'additional_cost' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['shipping_cost'] = $validated['shipping_cost'] ?? 0;
        $validated['additional_cost'] = $validated['additional_cost'] ?? 0;

        Transaction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan!'
        ]);
    }

    /**
     * Show transaction detail
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ]);
    }

    /**
     * Show form to edit transaction
     */
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ]);
    }

    /**
     * Update transaction
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'product_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'quantity' => 'required|integer|min:1',
            'modal_per_unit' => 'required|numeric|min:0',
            'selling_price_per_unit' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'additional_cost' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['shipping_cost'] = $validated['shipping_cost'] ?? 0;
        $validated['additional_cost'] = $validated['additional_cost'] ?? 0;

        $transaction->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diupdate!'
        ]);
    }

    /**
     * Delete transaction
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus!'
        ]);
    }

    /**
     * Export transactions to CSV, Excel, PDF, or JSON
     * ⭐ NOW: CSV works without packages! Excel & PDF require packages.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        $format = $request->input('format', 'csv'); // Default to CSV (no package needed!)
        
        // Get transactions
        $transactions = Transaction::dateRange($startDate, $endDate)
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        // Get statistics
        $statistics = $this->getStatistics($startDate, $endDate);
        
        // Export based on format
        switch ($format) {
            case 'csv':
                // ⭐ CSV - NO PACKAGE REQUIRED! WORKS NOW!
                return $this->exportCsv($request);
                
            case 'excel':
                // Excel - Requires maatwebsite/excel package
                if (class_exists('Maatwebsite\Excel\Facades\Excel') && 
                    class_exists('App\Exports\TransactionsExport')) {
                    return $this->exportExcel($startDate, $endDate);
                } else {
                    // Fallback to CSV if Excel package not installed
                    return $this->exportCsv($request);
                }
                
            case 'pdf':
                // PDF - Requires barryvdh/dompdf package
                if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                    return $this->exportPdf($transactions, $statistics, $startDate, $endDate);
                } else {
                    // Fallback to CSV if PDF package not installed
                    return $this->exportCsv($request);
                }
                
            case 'json':
            default:
                return response()->json([
                    'success' => true,
                    'data' => $transactions,
                    'statistics' => $statistics,
                    'period' => [
                        'start' => $startDate,
                        'end' => $endDate
                    ]
                ]);
        }
    }

    /**
     * Export to Excel
     * ⭐ NOTE: Requires maatwebsite/excel package
     * Uncomment imports at top after installing package
     */
    private function exportExcel($startDate, $endDate)
    {
        // Check if classes are available (package installed)
        if (!class_exists('Maatwebsite\Excel\Facades\Excel')) {
            return $this->exportCsv(request());
        }
        
        $filename = 'Laporan_Pembukuan_' . date('Ymd', strtotime($startDate)) . '-' . date('Ymd', strtotime($endDate)) . '.xlsx';
        
        // Dynamic class loading
        $excelClass = \Maatwebsite\Excel\Facades\Excel::class;
        $exportClass = \App\Exports\TransactionsExport::class;
        
        return $excelClass::download(
            new $exportClass($startDate, $endDate),
            $filename
        );
    }

    /**
     * Export to PDF
     * ⭐ NOTE: Requires barryvdh/dompdf package
     * Uncomment imports at top after installing package
     */
    private function exportPdf($transactions, $statistics, $startDate, $endDate)
    {
        // Check if class is available (package installed)
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return $this->exportCsv(request());
        }
        
        $pdfClass = \Barryvdh\DomPDF\Facade\Pdf::class;
        $pdf = $pdfClass::loadView('admin.bookkeeping.pdf', compact('transactions', 'statistics', 'startDate', 'endDate'));
        
        $filename = 'Laporan_Pembukuan_' . date('Ymd', strtotime($startDate)) . '-' . date('Ymd', strtotime($endDate)) . '.pdf';
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download($filename);
    }

    /**
     * Print PDF (view in browser)
     * ⭐ NOTE: Requires barryvdh/dompdf package
     */
    public function print(Request $request)
    {
        // Check if class is available (package installed)
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            // Redirect to CSV export if PDF not available
            return redirect()->route('admin.bookkeeping.export', ['format' => 'csv']);
        }
        
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        
        $transactions = Transaction::dateRange($startDate, $endDate)
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        $statistics = $this->getStatistics($startDate, $endDate);
        
        $pdfClass = \Barryvdh\DomPDF\Facade\Pdf::class;
        $pdf = $pdfClass::loadView('admin.bookkeeping.index', compact('transactions', 'statistics', 'startDate', 'endDate'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->stream('Laporan_Pembukuan.pdf');
    }

    /**
     * Export to CSV (Indonesian Excel compatible)
     * ⭐ WORKS WITHOUT ANY PACKAGE! Uses semicolon delimiter for Indonesian Excel
     */
    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        
        // Get transactions
        $transactions = Transaction::dateRange($startDate, $endDate)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        // Generate filename
        $filename = 'Laporan_Pembukuan_' . date('Ymd', strtotime($startDate)) . '-' . date('Ymd', strtotime($endDate)) . '.csv';
        
        // Set headers for download
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        // Create CSV content
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 (makes Excel recognize UTF-8 properly)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write header row - Using SEMICOLON for Indonesian Excel
            fputcsv($file, [
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
            ], ';'); // ← SEMICOLON delimiter for Indonesian Excel
            
            // Write data rows
            $no = 1;
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $no++,
                    $transaction->transaction_date->format('d/m/Y'),
                    $transaction->invoice_number,
                    $transaction->product_name,
                    $transaction->customer_name,
                    $transaction->customer_phone ?? '-',
                    $transaction->quantity,
                    $transaction->modal_per_unit,  // Raw number - let Excel format
                    $transaction->selling_price_per_unit,
                    $transaction->total_modal,
                    $transaction->total_selling,
                    $transaction->shipping_cost,
                    $transaction->additional_cost,
                    $transaction->total_cost,
                    $transaction->profit,
                    $this->getStatusLabel($transaction->payment_status),
                    $transaction->notes ?? '-',
                ], ';'); // ← SEMICOLON delimiter
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper: Get status label in Indonesian
     * ⭐ Used by CSV export
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

    // =============================================
    // ⭐ API METHODS (for Dashboard Integration)
    // =============================================

    /**
     * Get statistics API for dashboard cards
     * Route: GET /api/admin/bookkeeping/statistics
     */
    public function getStatisticsApi(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        
        $statistics = $this->getStatistics($startDate, $endDate);
        
        return response()->json([
            'success' => true,
            'statistics' => $statistics
        ]);
    }

    /**
     * Get monthly data API for charts
     * Route: GET /api/admin/bookkeeping/monthly-data
     */
    public function getMonthlyDataApi()
    {
        $monthlyData = $this->getMonthlyData();
        
        return response()->json([
            'success' => true,
            'data' => $monthlyData
        ]);
    }

    /**
     * Filter transactions API
     * Route: GET /api/admin/bookkeeping/filter
     */
    public function filterTransactions(Request $request)
    {
        $query = Transaction::query();

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by customer name
        if ($request->has('customer_name') && $request->customer_name != '') {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        // Filter by product name
        if ($request->has('product_name') && $request->product_name != '') {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        // Get results
        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);
    }
}