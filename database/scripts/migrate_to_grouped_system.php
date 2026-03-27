<?php

/**
 * ============================================================================
 * DATA MIGRATION SCRIPT
 * ============================================================================
 * 
 * Script ini untuk migrate data EXISTING ke system baru (grouped)
 * 
 * Jalankan setelah migration schema selesai:
 * php artisan migrate
 * 
 * Lalu jalankan script ini:
 * php artisan tinker
 * include 'migrate_to_grouped_system.php';
 * 
 * Atau buat sebagai Artisan Command
 * ============================================================================
 */

use App\Models\InventoryItem;
use App\Models\InventorySerialNumber;
use Illuminate\Support\Facades\DB;

class MigrateToGroupedSystem
{
    public function migrate()
    {
        echo "🚀 Starting migration to grouped inventory system...\n\n";

        DB::beginTransaction();

        try {
            // Step 1: Get all existing inventory items yang belum di-group
            $existingItems = InventoryItem::where('is_grouped', false)
                ->orWhereNull('is_grouped')
                ->get();

            echo "📊 Found {$existingItems->count()} items to migrate\n\n";

            if ($existingItems->count() == 0) {
                echo "✅ No items to migrate. System already grouped!\n";
                return;
            }

            // Step 2: Group items by product (product_name + brand + supplier)
            $grouped = $existingItems->groupBy(function($item) {
                return $item->product_name . '|' . $item->brand . '|' . ($item->supplier ?? 'null');
            });

            echo "📦 Grouped into " . $grouped->count() . " unique products\n\n";

            $migrated = 0;
            $errors = [];

            // Step 3: Process each group
            foreach ($grouped as $key => $items) {
                try {
                    echo "Processing: {$key}\n";
                    echo "  - Items count: {$items->count()}\n";

                    // Take first item as master
                    $master = $items->first();

                    // Create or update master product
                    $product = InventoryItem::updateOrCreate(
                        [
                            'product_name' => $master->product_name,
                            'brand' => $master->brand,
                            'supplier' => $master->supplier,
                        ],
                        [
                            'category' => $master->category,
                            'description' => $master->description,
                            'purchase_price' => $master->purchase_price,
                            'selling_price' => $master->selling_price,
                            'warehouse_location' => $master->warehouse_location,
                            'stock' => 0, // Will be calculated
                            'status' => 'in_stock',
                            'entry_date' => $master->entry_date,
                            'is_grouped' => true,
                            'serial_number' => null, // Clear deprecated field
                        ]
                    );

                    echo "  - Master product ID: {$product->id}\n";

                    $stockCount = 0;

                    // Move all serial numbers to new table
                    foreach ($items as $item) {
                        // Skip if this is the master product itself
                        if ($item->id == $product->id) {
                            continue;
                        }

                        // Create serial number entry
                        if ($item->serial_number) {
                            try {
                                InventorySerialNumber::create([
                                    'inventory_item_id' => $product->id,
                                    'serial_number' => $item->serial_number,
                                    'status' => $item->status ?? 'in_stock',
                                    'customer_name' => $item->customer_name,
                                    'customer_phone' => $item->customer_phone,
                                    'selling_price' => $item->selling_price,
                                    'sold_date' => $item->exit_date,
                                    'exit_date' => $item->exit_date,
                                    'exit_reason' => $item->exit_reason,
                                    'notes' => $item->notes,
                                    'scanned_by' => $item->scanned_by,
                                    'entry_date' => $item->entry_date,
                                ]);

                                // Count stock (only in_stock items)
                                if (($item->status ?? 'in_stock') === 'in_stock') {
                                    $stockCount++;
                                }

                                // Delete old item
                                $item->delete();

                            } catch (\Exception $e) {
                                echo "    ⚠️  Error migrating SN {$item->serial_number}: {$e->getMessage()}\n";
                                $errors[] = [
                                    'serial_number' => $item->serial_number,
                                    'error' => $e->getMessage()
                                ];
                            }
                        }
                    }

                    // Update master product stock
                    $product->update(['stock' => $stockCount]);

                    echo "  - ✅ Migrated {$items->count()} items, stock: {$stockCount}\n\n";

                    $migrated += $items->count();

                } catch (\Exception $e) {
                    echo "  - ❌ Error processing group: {$e->getMessage()}\n\n";
                    $errors[] = [
                        'product_key' => $key,
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            echo "\n";
            echo "====================================\n";
            echo "✅ MIGRATION COMPLETED!\n";
            echo "====================================\n";
            echo "Total items processed: {$migrated}\n";
            echo "Unique products: " . $grouped->count() . "\n";
            echo "Errors: " . count($errors) . "\n";

            if (count($errors) > 0) {
                echo "\n⚠️  Errors:\n";
                foreach ($errors as $error) {
                    echo "  - " . ($error['serial_number'] ?? $error['product_key']) . ": " . $error['error'] . "\n";
                }
            }

            echo "\n";
            echo "🎉 System is now using grouped inventory!\n";
            echo "You can now scan multiple items of same product and stock will auto-increment.\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n";
            echo "❌ MIGRATION FAILED!\n";
            echo "Error: " . $e->getMessage() . "\n";
            echo "\nDatabase rolled back. No changes made.\n";
            throw $e;
        }
    }

    /**
     * Rollback migration (if needed)
     */
    public function rollback()
    {
        echo "⚠️  Rolling back migration...\n\n";

        DB::beginTransaction();

        try {
            // Get all grouped products
            $products = InventoryItem::where('is_grouped', true)->get();

            echo "Found {$products->count()} grouped products to rollback\n\n";

            foreach ($products as $product) {
                echo "Rolling back: {$product->product_name} | {$product->brand}\n";

                // Get all serial numbers for this product
                $serials = InventorySerialNumber::where('inventory_item_id', $product->id)->get();

                echo "  - Serial numbers: {$serials->count()}\n";

                // Recreate individual items
                foreach ($serials as $serial) {
                    InventoryItem::create([
                        'serial_number' => $serial->serial_number,
                        'product_name' => $product->product_name,
                        'brand' => $product->brand,
                        'category' => $product->category,
                        'description' => $product->description,
                        'purchase_price' => $product->purchase_price,
                        'selling_price' => $serial->selling_price ?? $product->selling_price,
                        'supplier' => $product->supplier,
                        'warehouse_location' => $product->warehouse_location,
                        'stock' => 1,
                        'status' => $serial->status,
                        'entry_date' => $serial->entry_date,
                        'exit_date' => $serial->exit_date,
                        'exit_reason' => $serial->exit_reason,
                        'customer_name' => $serial->customer_name,
                        'customer_phone' => $serial->customer_phone,
                        'notes' => $serial->notes,
                        'scanned_by' => $serial->scanned_by,
                        'is_grouped' => false,
                    ]);

                    // Delete serial number entry
                    $serial->delete();
                }

                // Delete grouped product
                $product->delete();

                echo "  - ✅ Rolled back\n\n";
            }

            DB::commit();

            echo "\n✅ Rollback completed!\n";
            echo "System reverted to individual item tracking.\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n❌ Rollback failed: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}

// ============================================================================
// USAGE
// ============================================================================

/*

// In Laravel Tinker or Artisan Command:

// Migrate to grouped system
$migrator = new MigrateToGroupedSystem();
$migrator->migrate();

// If you need to rollback
$migrator->rollback();

*/