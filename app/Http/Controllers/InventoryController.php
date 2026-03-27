<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display inventory dashboard
     */
    public function index()
    {
        $items = InventoryItem::latest()->get();
        
        // Hitung total stock
        $totalStock = InventoryItem::sum('stock');
        $totalItems = InventoryItem::count();
        $inStock = InventoryItem::where('stock', '>', 0)->count();
        $lowStock = InventoryItem::lowStock()->count();
        $outOfStock = InventoryItem::outOfStock()->count();
        
        return view('admin.inventory.index', compact('items', 'totalStock', 'totalItems', 'inStock', 'lowStock', 'outOfStock'));
    }

    /**
     * Display incoming items page (standard mode)
     */
    public function incoming()
    {
        return view('admin.inventory.incoming');
    }

    /**
     * Display outgoing items page
     */
    public function outgoing()
    {
        return view('admin.inventory.outgoing');
    }

    /**
     * Display reports page
     */
    public function reports()
    {
        return view('admin.inventory.reports');
    }

    /**
     * Get all inventory items (API)
     */
    public function getItems(Request $request)
    {
        try {
            $query = InventoryItem::query();

            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Filter by brand
            if ($request->has('brand') && $request->brand !== '') {
                $query->where('brand', $request->brand);
            }

            // Filter by category
            if ($request->has('category') && $request->category !== '') {
                $query->where('category', $request->category);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from !== '') {
                $query->whereDate('entry_date', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to !== '') {
                $query->whereDate('entry_date', '<=', $request->date_to);
            }

            // Search
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('serial_number', 'LIKE', "%{$search}%")
                      ->orWhere('product_name', 'LIKE', "%{$search}%")
                      ->orWhere('brand', 'LIKE', "%{$search}%")
                      ->orWhere('supplier', 'LIKE', "%{$search}%")
                      ->orWhere('customer_name', 'LIKE', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 50);
            $items = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics (API)
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_items' => InventoryItem::count(),
                'total_stock' => InventoryItem::sum('stock'),
                'in_stock' => InventoryItem::where('status', 'in_stock')->count(),
                'sold' => InventoryItem::where('status', 'sold')->count(),
                'damaged' => InventoryItem::where('status', 'damaged')->count(),
                'returned' => InventoryItem::where('status', 'returned')->count(),
                'low_stock' => InventoryItem::lowStock()->count(),
                'out_of_stock' => InventoryItem::outOfStock()->count(),
                'total_value' => InventoryItem::where('status', 'in_stock')->sum('purchase_price'),
                'total_revenue' => InventoryItem::where('status', 'sold')->sum('selling_price'),
                'total_profit' => InventoryItem::where('status', 'sold')
                    ->selectRaw('SUM(selling_price - purchase_price) as profit')
                    ->value('profit') ?? 0,
                'today_incoming' => InventoryItem::whereDate('entry_date', today())->count(),
                'today_outgoing' => InventoryItem::whereDate('exit_date', today())->count(),
                'this_month_incoming' => InventoryItem::whereMonth('entry_date', now()->month)
                    ->whereYear('entry_date', now()->year)
                    ->count(),
                'this_month_outgoing' => InventoryItem::whereDate('exit_date', now()->month)
                    ->whereYear('exit_date', now()->year)
                    ->count(),
            ];

            // Top brands
            $topBrands = InventoryItem::select('brand', DB::raw('count(*) as total'))
                ->where('status', 'in_stock')
                ->groupBy('brand')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            // Recent activities
            $recentActivities = InventoryItem::latest()
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'statistics' => $stats,
                'top_brands' => $topBrands,
                'recent_activities' => $recentActivities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if serial number exists
     */
    public function checkSerialNumber(Request $request)
    {
        try {
            // Log untuk debugging
            \Log::info('Check Serial Request:', $request->all());
            
            $serialNumber = $request->input('serial_number');
            
            // Validasi input
            if (!$serialNumber || trim($serialNumber) === '') {
                return response()->json([
                    'success' => false,
                    'error' => 'Serial number tidak boleh kosong'
                ], 400);
            }
            
            $item = InventoryItem::where('serial_number', $serialNumber)->first();
            
            if ($item) {
                return response()->json([
                    'success' => true,
                    'exists' => true,
                    'message' => 'Serial number sudah terdaftar',
                    'item' => [
                        'id' => $item->id,
                        'serial_number' => $item->serial_number,
                        'product_name' => $item->product_name,
                        'brand' => $item->brand,
                        'status' => $item->status,
                        'stock' => $item->stock ?? 0,
                        'entry_date' => $item->entry_date
                    ]
                ]);
            }
            
            return response()->json([
                'success' => true,
                'exists' => false,
                'message' => 'Serial number tersedia'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error checking serial number: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check multiple serial numbers at once (Batch)
     */
    public function checkSerialNumbersBatch(Request $request)
    {
        try {
            $serialNumbers = $request->input('serial_numbers', []);
            
            if (!is_array($serialNumbers) || empty($serialNumbers)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Serial numbers array is required'
                ], 400);
            }

            $results = [];
            
            foreach ($serialNumbers as $serialNumber) {
                $item = InventoryItem::where('serial_number', $serialNumber)->first();
                
                $results[] = [
                    'serial_number' => $serialNumber,
                    'exists' => $item ? true : false,
                    'item' => $item ? [
                        'id' => $item->id,
                        'product_name' => $item->product_name,
                        'brand' => $item->brand,
                        'status' => $item->status,
                        'stock' => $item->stock ?? 0
                    ] : null
                ];
            }

            $existingCount = collect($results)->where('exists', true)->count();
            $availableCount = collect($results)->where('exists', false)->count();

            return response()->json([
                'success' => true,
                'total' => count($results),
                'existing' => $existingCount,
                'available' => $availableCount,
                'results' => $results
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check stock by product name/brand
     */
    public function checkStock(Request $request)
    {
        $query = InventoryItem::query();

        // Search by product name
        if ($request->has('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        // Additional filters
        if ($request->has('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->orderBy('entry_date', 'desc')->get();

        // Group by status
        $grouped = [
            'in_stock' => $items->where('status', 'in_stock')->values(),
            'sold' => $items->where('status', 'sold')->values(),
            'damaged' => $items->where('status', 'damaged')->values(),
            'returned' => $items->where('status', 'returned')->values(),
        ];

        return response()->json([
            'success' => true,
            'items' => $items,
            'grouped' => $grouped,
            'summary' => [
                'total' => $items->count(),
                'total_stock' => $items->sum('stock'),
                'in_stock' => $grouped['in_stock']->count(),
                'sold' => $grouped['sold']->count(),
                'damaged' => $grouped['damaged']->count(),
                'returned' => $grouped['returned']->count(),
            ]
        ]);
    }

    /**
     * Get stock summary (for quick overview)
     */
    public function getStockSummary(Request $request)
    {
        $summary = InventoryItem::select(
                'product_name',
                'brand',
                'category',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(stock) as total_stock'),
                DB::raw('SUM(CASE WHEN status = "in_stock" THEN 1 ELSE 0 END) as in_stock'),
                DB::raw('SUM(CASE WHEN status = "sold" THEN 1 ELSE 0 END) as sold'),
                DB::raw('SUM(CASE WHEN status = "damaged" THEN 1 ELSE 0 END) as damaged')
            )
            ->groupBy('product_name', 'brand', 'category')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'summary' => $summary
        ]);
    }

    /**
     * Store incoming item (Single)
     * Dengan logika stock
     */
    public function storeIncoming(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'serial_number' => 'required|string',
                'product_name' => 'required|string|max:255',
                'brand' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'nullable|numeric|min:0',
                'supplier' => 'nullable|string|max:255',
                'warehouse_location' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cek apakah barang dengan serial number yang sama sudah ada
            $existing = InventoryItem::where('serial_number', $request->serial_number)->first();
            
            if ($existing) {
                // Jika sudah ada, tambah stock
                $existing->addStock(1);
                
                return response()->json([
                    'success' => true,
                    'message' => "Stock {$existing->product_name} bertambah. Total stock: {$existing->stock}",
                    'item' => $existing
                ]);
            }

            // Jika baru, buat record baru dengan stock = 1
            $item = InventoryItem::create([
                'serial_number' => $request->serial_number,
                'product_name' => $request->product_name,
                'brand' => $request->brand,
                'category' => $request->category,
                'description' => $request->description,
                'purchase_price' => $request->purchase_price,
                'selling_price' => $request->selling_price ?? 0,
                'supplier' => $request->supplier,
                'warehouse_location' => $request->warehouse_location,
                'notes' => $request->notes,
                'stock' => 1,
                'status' => 'in_stock',
                'entry_date' => now(),
                'scanned_by' => session('admin_name', 'System'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan ke inventory',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store multiple incoming items (Batch Import) - OLD METHOD
     * Method lama - tetap ada untuk backward compatibility
     */
    public function storeIncomingBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'items' => 'required|array|min:1',
                'items.*.serial_number' => 'required|distinct',
                'items.*.product_name' => 'required|string|max:255',
                'items.*.brand' => 'nullable|string|max:255',
                'items.*.category' => 'nullable|string|max:255',
                'items.*.description' => 'nullable|string',
                'items.*.purchase_price' => 'required|numeric|min:0',
                'items.*.selling_price' => 'nullable|numeric|min:0',
                'items.*.supplier' => 'nullable|string|max:255',
                'items.*.warehouse_location' => 'nullable|string|max:255',
                'items.*.notes' => 'nullable|string',
            ], [
                'items.*.serial_number.distinct' => 'Serial number :input duplikat dalam list',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $savedItems = [];
            $errors = [];
            $scannedBy = session('admin_name', 'System');

            DB::beginTransaction();
            
            try {
                foreach ($request->items as $index => $itemData) {
                    try {
                        // Check if serial already exists
                        $existing = InventoryItem::where('serial_number', $itemData['serial_number'])->first();
                        
                        if ($existing) {
                            $errors[] = [
                                'index' => $index + 1,
                                'serial_number' => $itemData['serial_number'],
                                'error' => 'Serial number sudah terdaftar'
                            ];
                            continue;
                        }

                        $item = InventoryItem::create([
                            'serial_number' => $itemData['serial_number'],
                            'product_name' => $itemData['product_name'],
                            'brand' => $itemData['brand'] ?? null,
                            'category' => $itemData['category'] ?? null,
                            'description' => $itemData['description'] ?? null,
                            'purchase_price' => $itemData['purchase_price'],
                            'selling_price' => $itemData['selling_price'] ?? 0,
                            'supplier' => $itemData['supplier'] ?? null,
                            'warehouse_location' => $itemData['warehouse_location'] ?? null,
                            'notes' => $itemData['notes'] ?? null,
                            'stock' => 1,
                            'status' => 'in_stock',
                            'entry_date' => now(),
                            'scanned_by' => $scannedBy,
                        ]);

                        $savedItems[] = $item;
                        
                    } catch (\Exception $e) {
                        $errors[] = [
                            'index' => $index + 1,
                            'serial_number' => $itemData['serial_number'],
                            'error' => $e->getMessage()
                        ];
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => count($savedItems) . ' item berhasil disimpan' . 
                               (count($errors) > 0 ? ', ' . count($errors) . ' item gagal' : ''),
                    'saved_count' => count($savedItems),
                    'error_count' => count($errors),
                    'saved_items' => $savedItems,
                    'errors' => $errors
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Batch import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ NEW METHOD: Store multiple incoming items - OPTIMIZED VERSION
     * 100x lebih cepat dari method lama!
     */
    public function storeIncomingBatchOptimized(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'items' => 'required|array|min:1',
                'items.*.serial_number' => 'required|distinct',
                'items.*.product_name' => 'required|string|max:255',
                'items.*.brand' => 'nullable|string|max:255',
                'items.*.category' => 'nullable|string|max:255',
                'items.*.description' => 'nullable|string',
                'items.*.purchase_price' => 'required|numeric|min:0',
                'items.*.selling_price' => 'nullable|numeric|min:0',
                'items.*.supplier' => 'nullable|string|max:255',
                'items.*.warehouse_location' => 'nullable|string|max:255',
                'items.*.notes' => 'nullable|string',
            ], [
                'items.*.serial_number.distinct' => 'Serial number :input duplikat dalam list',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $savedItems = [];
            $errors = [];
            $scannedBy = session('admin_name', 'System');

            DB::beginTransaction();
            
            try {
                // ✅ OPTIMIZATION: Query database SEKALI untuk semua serial numbers
                $requestedSerials = collect($request->items)->pluck('serial_number')->toArray();
                
                // Single query untuk check semua SN sekaligus
                $existingSerials = InventoryItem::whereIn('serial_number', $requestedSerials)
                    ->pluck('serial_number')
                    ->toArray();
                
                // Buat map untuk O(1) lookup (instant check)
                $existingSerialsMap = array_flip($existingSerials);

                foreach ($request->items as $index => $itemData) {
                    try {
                        $serialNumber = $itemData['serial_number'];
                        
                        // ✅ Check menggunakan in-memory map (sangat cepat, tidak query database)
                        if (isset($existingSerialsMap[$serialNumber])) {
                            $errors[] = [
                                'index' => $index + 1,
                                'serial_number' => $serialNumber,
                                'error' => 'Serial number sudah terdaftar'
                            ];
                            continue;
                        }

                        // Create new item
                        $item = InventoryItem::create([
                            'serial_number' => $serialNumber,
                            'product_name' => $itemData['product_name'],
                            'brand' => $itemData['brand'] ?? null,
                            'category' => $itemData['category'] ?? null,
                            'description' => $itemData['description'] ?? null,
                            'purchase_price' => $itemData['purchase_price'],
                            'selling_price' => $itemData['selling_price'] ?? 0,
                            'supplier' => $itemData['supplier'] ?? null,
                            'warehouse_location' => $itemData['warehouse_location'] ?? null,
                            'notes' => $itemData['notes'] ?? null,
                            'stock' => 1,
                            'status' => 'in_stock',
                            'entry_date' => now(),
                            'scanned_by' => $scannedBy,
                        ]);

                        $savedItems[] = $item;
                        
                        // Add to map untuk prevent duplicate dalam batch yang sama
                        $existingSerialsMap[$serialNumber] = true;
                        
                    } catch (\Exception $e) {
                        $errors[] = [
                            'index' => $index + 1,
                            'serial_number' => $itemData['serial_number'],
                            'error' => $e->getMessage()
                        ];
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => count($savedItems) . ' item berhasil disimpan' . 
                               (count($errors) > 0 ? ', ' . count($errors) . ' item gagal' : ''),
                    'saved_count' => count($savedItems),
                    'error_count' => count($errors),
                    'saved_items' => $savedItems,
                    'errors' => $errors
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Batch import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update stock (untuk barang masuk/keluar manual)
     */
    public function updateStock(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
                'type' => 'required|in:masuk,keluar',
                'notes' => 'nullable|string'
            ]);

            $item = InventoryItem::findOrFail($id);
            
            if ($request->type == 'masuk') {
                $item->addStock($request->quantity, $request->notes);
                $message = "Stock bertambah {$request->quantity} unit";
            } else {
                $item->reduceStock($request->quantity, $request->notes);
                $message = "Stock berkurang {$request->quantity} unit";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'item' => $item->fresh(),
                'new_stock' => $item->stock
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Process outgoing item (sell/damage/return)
     */
    public function processOutgoing(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'serial_number' => 'required|exists:inventory_items,serial_number',
                'action' => 'required|in:sold,damaged,returned',
                'customer_name' => 'required_if:action,sold|nullable|string|max:255',
                'customer_phone' => 'required_if:action,sold|nullable|string|max:20',
                'selling_price' => 'required_if:action,sold|nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $item = InventoryItem::where('serial_number', $request->serial_number)->firstOrFail();

            // Check if item is still in stock
            if ($item->status !== 'in_stock') {
                return response()->json([
                    'success' => false,
                    'error' => "Item dengan SN {$request->serial_number} tidak tersedia. Status: {$item->status}"
                ], 400);
            }

            // Process based on action
            switch ($request->action) {
                case 'sold':
                    $item->markAsSold(
                        $request->customer_name,
                        $request->customer_phone,
                        $request->selling_price,
                        $request->notes
                    );
                    $message = 'Item berhasil dijual';
                    break;

                case 'damaged':
                    $item->markAsDamaged($request->notes);
                    $message = 'Item berhasil ditandai sebagai rusak';
                    break;

                case 'returned':
                    $item->update([
                        'status' => 'returned',
                        'exit_date' => now(),
                        'exit_reason' => 'returned',
                        'notes' => $request->notes,
                    ]);
                    $message = 'Item berhasil di-return';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'item' => $item->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single item detail
     */
    public function show($id)
    {
        try {
            $item = InventoryItem::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Item not found'
            ], 404);
        }
    }

    /**
     * Update item
     */
    public function update(Request $request, $id)
    {
        try {
            $item = InventoryItem::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'serial_number' => 'required|unique:inventory_items,serial_number,' . $id,
                'product_name' => 'required|string|max:255',
                'brand' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'purchase_price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $item->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil diupdate',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete item
     */
    public function destroy($id)
    {
        try {
            $item = InventoryItem::findOrFail($id);
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export inventory data
     */
    public function export(Request $request)
    {
        try {
            $query = InventoryItem::query();

            // Apply filters if provided
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            if ($request->has('date_from') && $request->date_from !== '') {
                $query->whereDate('entry_date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to !== '') {
                $query->whereDate('entry_date', '<=', $request->date_to);
            }

            $items = $query->orderBy('entry_date', 'desc')->get();

            // Generate CSV
            $filename = 'inventory_export_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($items) {
                $file = fopen('php://output', 'w');
                
                // Header
                fputcsv($file, [
                    'Serial Number',
                    'Product Name',
                    'Brand',
                    'Category',
                    'Stock',
                    'Purchase Price',
                    'Selling Price',
                    'Supplier',
                    'Status',
                    'Entry Date',
                    'Exit Date',
                    'Customer Name',
                    'Customer Phone',
                    'Warehouse Location',
                    'Notes'
                ]);

                // Data
                foreach ($items as $item) {
                    fputcsv($file, [
                        $item->serial_number,
                        $item->product_name,
                        $item->brand,
                        $item->category,
                        $item->stock ?? 0,
                        $item->purchase_price,
                        $item->selling_price,
                        $item->supplier,
                        $item->status,
                        $item->entry_date ? $item->entry_date->format('Y-m-d H:i:s') : '',
                        $item->exit_date ? $item->exit_date->format('Y-m-d H:i:s') : '',
                        $item->customer_name,
                        $item->customer_phone,
                        $item->warehouse_location,
                        $item->notes
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Get brands list
     */
    public function getBrands()
    {
        try {
            $brands = InventoryItem::select('brand')
                ->distinct()
                ->whereNotNull('brand')
                ->orderBy('brand')
                ->pluck('brand');

            return response()->json([
                'success' => true,
                'brands' => $brands
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories list
     */
    public function getCategories()
    {
        try {
            $categories = InventoryItem::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->orderBy('category')
                ->pluck('category');

            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate bulk serial numbers
     */
    public function validateBulkSerialNumbers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'serial_numbers' => 'required|array|min:1',
                'serial_numbers.*' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $serialNumbers = $request->serial_numbers;
            
            // Query database sekali untuk semua SN (efficient)
            $existingItems = InventoryItem::whereIn('serial_number', $serialNumbers)
                ->get(['serial_number', 'product_name', 'brand', 'stock', 'status']);

            // Buat map untuk quick lookup
            $existingMap = $existingItems->keyBy('serial_number');
            
            // Map hasil untuk setiap serial number
            $results = collect($serialNumbers)->map(function($sn) use ($existingMap) {
                $exists = isset($existingMap[$sn]);
                
                return [
                    'serial_number' => $sn,
                    'exists' => $exists,
                    'can_add' => !$exists,
                    'item' => $exists ? [
                        'product_name' => $existingMap[$sn]->product_name,
                        'brand' => $existingMap[$sn]->brand,
                        'stock' => $existingMap[$sn]->stock,
                        'status' => $existingMap[$sn]->status,
                    ] : null
                ];
            });

            // Summary statistics
            $summary = [
                'total' => count($serialNumbers),
                'can_add' => $results->where('can_add', true)->count(),
                'already_exists' => $results->where('exists', true)->count(),
            ];

            return response()->json([
                'success' => true,
                'summary' => $summary,
                'results' => $results->values()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bulk scan statistics
     */
    public function getBulkScanStatistics(Request $request)
    {
        try {
            // Default date range: last 30 days
            $dateFrom = $request->input('date_from', now()->subDays(30));
            $dateTo = $request->input('date_to', now());

            $stats = [
                // Total items yang ditambahkan dalam periode
                'total_items_added' => InventoryItem::whereBetween('entry_date', [$dateFrom, $dateTo])
                    ->count(),
                
                // Items yang ditambahkan per scanner/user
                'total_by_scanner' => InventoryItem::whereBetween('entry_date', [$dateFrom, $dateTo])
                    ->select('scanned_by', DB::raw('COUNT(*) as count'))
                    ->groupBy('scanned_by')
                    ->orderByDesc('count')
                    ->get(),
                
                // Aktivitas scan harian
                'daily_scans' => InventoryItem::whereBetween('entry_date', [$dateFrom, $dateTo])
                    ->select(DB::raw('DATE(entry_date) as date'), DB::raw('COUNT(*) as count'))
                    ->groupBy(DB::raw('DATE(entry_date)'))
                    ->orderBy('date', 'desc')
                    ->get(),
                
                // Top 10 produk yang paling banyak di-scan
                'top_products' => InventoryItem::whereBetween('entry_date', [$dateFrom, $dateTo])
                    ->select('product_name', 'brand', DB::raw('COUNT(*) as count'))
                    ->groupBy('product_name', 'brand')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->get(),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $stats,
                'period' => [
                    'from' => $dateFrom,
                    'to' => $dateTo
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // GROUPED INVENTORY METHODS
    // ========================================

    /**
     * Get items grouped by product
     */
    public function getItemsGrouped(Request $request)
    {
        try {
            $query = InventoryItem::select(
                    'product_name',
                    'brand',
                    'supplier',
                    'category',
                    'purchase_price',
                    'selling_price',
                    DB::raw('SUM(stock) as total_stock'),
                    DB::raw('COUNT(*) as total_items'),
                    DB::raw('MAX(id) as sample_id'),
                    DB::raw('MAX(entry_date) as last_entry'),
                    DB::raw('GROUP_CONCAT(DISTINCT warehouse_location) as locations')
                )
                ->where('is_grouped', true)
                ->groupBy('product_name', 'brand', 'supplier', 'category', 'purchase_price', 'selling_price');

            if ($request->has('in_stock_only') && $request->in_stock_only) {
                $query->having('total_stock', '>', 0);
            }

            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('product_name', 'LIKE', "%{$search}%")
                      ->orWhere('brand', 'LIKE', "%{$search}%")
                      ->orWhere('supplier', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('brand') && $request->brand !== '') {
                $query->where('brand', $request->brand);
            }

            if ($request->has('category') && $request->category !== '') {
                $query->where('category', $request->category);
            }

            $sortBy = $request->get('sort_by', 'last_entry');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 50);
            $items = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'items' => $items
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get serial numbers for a specific grouped product
     */
    public function getProductSerials($id, Request $request)
    {
        try {
            $sampleItem = InventoryItem::findOrFail($id);

            $query = InventoryItem::where('product_name', $sampleItem->product_name)
                ->where('brand', $sampleItem->brand)
                ->where('supplier', $sampleItem->supplier);

            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            if ($request->has('available_only') && $request->available_only) {
                $query->where('status', 'in_stock')
                      ->where('stock', '>', 0);
            }

            $serials = $query->orderBy('entry_date', 'desc')->get();

            return response()->json([
                'success' => true,
                'product_info' => [
                    'product_name' => $sampleItem->product_name,
                    'brand' => $sampleItem->brand,
                    'supplier' => $sampleItem->supplier,
                    'category' => $sampleItem->category,
                ],
                'total_stock' => $serials->sum('stock'),
                'total_items' => $serials->count(),
                'serials' => $serials,
                'summary' => [
                    'in_stock' => $serials->where('status', 'in_stock')->count(),
                    'sold' => $serials->where('status', 'sold')->count(),
                    'damaged' => $serials->where('status', 'damaged')->count(),
                    'returned' => $serials->where('status', 'returned')->count(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store incoming items with grouping support
     */
    public function storeIncomingGrouped(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'supplier' => 'required|string|max:255',
                'category' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'nullable|numeric|min:0',
                'warehouse_location' => 'nullable|string|max:255',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $existingItem = InventoryItem::where('product_name', $request->product_name)
                    ->where('brand', $request->brand)
                    ->where('supplier', $request->supplier)
                    ->where('is_grouped', true)
                    ->first();

                if ($existingItem) {
                    $existingItem->addStock($request->quantity, $request->notes);
                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => "Stock {$existingItem->product_name} bertambah {$request->quantity} unit. Total stock: {$existingItem->stock}",
                        'item' => $existingItem->fresh()
                    ]);
                }

                $newItem = InventoryItem::create([
                    'serial_number' => null,
                    'product_name' => $request->product_name,
                    'brand' => $request->brand,
                    'supplier' => $request->supplier,
                    'category' => $request->category,
                    'description' => $request->description,
                    'purchase_price' => $request->purchase_price,
                    'selling_price' => $request->selling_price ?? 0,
                    'warehouse_location' => $request->warehouse_location,
                    'notes' => $request->notes,
                    'stock' => $request->quantity,
                    'is_grouped' => true,
                    'status' => 'in_stock',
                    'entry_date' => now(),
                    'scanned_by' => session('admin_name', 'System'),
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => "Produk baru berhasil ditambahkan dengan stock {$request->quantity} unit",
                    'item' => $newItem
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process outgoing for grouped items
     */
    public function processOutgoingGrouped(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|exists:inventory_items,id',
                'quantity' => 'required|integer|min:1',
                'action' => 'required|in:sold,damaged,returned',
                'customer_name' => 'required_if:action,sold|nullable|string|max:255',
                'customer_phone' => 'required_if:action,sold|nullable|string|max:20',
                'selling_price' => 'required_if:action,sold|nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $item = InventoryItem::findOrFail($request->item_id);

            if (!$item->is_grouped) {
                return response()->json([
                    'success' => false,
                    'error' => 'Item ini bukan grouped inventory.'
                ], 400);
            }

            if ($item->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'error' => "Stock tidak cukup. Available: {$item->stock}, Requested: {$request->quantity}"
                ], 400);
            }

            DB::beginTransaction();

            try {
                $item->reduceStock($request->quantity, $request->notes);

                if ($request->action === 'sold') {
                    $salesNote = "SOLD - {$request->quantity} unit to {$request->customer_name} ({$request->customer_phone})";
                    if ($request->notes) {
                        $salesNote .= " | Notes: {$request->notes}";
                    }

                    $item->update([
                        'notes' => ($item->notes ? $item->notes . "\n" : '') . $salesNote
                    ]);
                }

                DB::commit();

                $message = match($request->action) {
                    'sold' => "{$request->quantity} unit berhasil dijual",
                    'damaged' => "{$request->quantity} unit ditandai sebagai rusak",
                    'returned' => "{$request->quantity} unit di-return",
                    default => "Stock berkurang {$request->quantity} unit"
                };

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'item' => $item->fresh(),
                    'remaining_stock' => $item->stock
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert single items to grouped inventory
     */
    public function convertToGrouped(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string',
                'brand' => 'required|string',
                'supplier' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $items = InventoryItem::where('product_name', $request->product_name)
                    ->where('brand', $request->brand)
                    ->where('supplier', $request->supplier)
                    ->where('is_grouped', false)
                    ->get();

                if ($items->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Tidak ada item yang ditemukan untuk di-convert'
                    ], 404);
                }

                $totalStock = $items->sum('stock');
                $template = $items->first();

                $groupedItem = InventoryItem::create([
                    'serial_number' => null,
                    'product_name' => $template->product_name,
                    'brand' => $template->brand,
                    'supplier' => $template->supplier,
                    'category' => $template->category,
                    'description' => $template->description,
                    'purchase_price' => $template->purchase_price,
                    'selling_price' => $template->selling_price,
                    'warehouse_location' => $template->warehouse_location,
                    'stock' => $totalStock,
                    'is_grouped' => true,
                    'status' => 'in_stock',
                    'entry_date' => now(),
                    'scanned_by' => session('admin_name', 'System'),
                    'notes' => "Converted from {$items->count()} individual items",
                ]);

                InventoryItem::whereIn('id', $items->pluck('id'))->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => "{$items->count()} item berhasil di-convert menjadi grouped inventory",
                    'grouped_item' => $groupedItem,
                    'total_stock' => $totalStock
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get grouped inventory statistics
     */
    public function getGroupedStatistics()
    {
        try {
            $stats = [
                'total_grouped_products' => InventoryItem::where('is_grouped', true)
                    ->select('product_name', 'brand', 'supplier')
                    ->distinct()
                    ->count(),

                'total_grouped_stock' => InventoryItem::where('is_grouped', true)
                    ->sum('stock'),

                'grouped_by_category' => InventoryItem::where('is_grouped', true)
                    ->select('category', DB::raw('COUNT(DISTINCT CONCAT(product_name, brand, supplier)) as products'), DB::raw('SUM(stock) as total_stock'))
                    ->groupBy('category')
                    ->get(),

                'top_grouped_products' => InventoryItem::where('is_grouped', true)
                    ->select('product_name', 'brand', 'stock')
                    ->orderByDesc('stock')
                    ->limit(10)
                    ->get(),

                'low_stock_grouped' => InventoryItem::where('is_grouped', true)
                    ->where('stock', '>', 0)
                    ->where('stock', '<=', 5)
                    ->select('product_name', 'brand', 'stock')
                    ->orderBy('stock', 'asc')
                    ->get(),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search grouped products
     */
    public function searchGroupedProducts(Request $request)
    {
        try {
            $search = $request->input('q', '');

            if (strlen($search) < 2) {
                return response()->json([
                    'success' => false,
                    'error' => 'Search query harus minimal 2 karakter'
                ], 400);
            }

            $products = InventoryItem::select(
                    'id',
                    'product_name',
                    'brand',
                    'supplier',
                    'stock',
                    'purchase_price',
                    'selling_price'
                )
                ->where('is_grouped', true)
                ->where(function($q) use ($search) {
                    $q->where('product_name', 'LIKE', "%{$search}%")
                      ->orWhere('brand', 'LIKE', "%{$search}%")
                      ->orWhere('supplier', 'LIKE', "%{$search}%");
                })
                ->where('stock', '>', 0)
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}