<?php

use App\Models\Product;
use App\Models\SupplierLocation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Product::query()
            ->whereNotNull('supplier_location_id')
            ->where(function ($q) {
                $q->whereNull('supplier_location')->orWhere('supplier_location', '');
            })
            ->orderBy('id')
            ->chunkById(100, function ($products) {
                foreach ($products as $product) {
                    $location = SupplierLocation::find($product->supplier_location_id);
                    if (! $location) {
                        continue;
                    }

                    $parts = array_values(array_unique(array_filter([$location->name, $location->country])));
                    $label = $parts !== [] ? implode(', ', $parts) : $location->name;

                    if ($label) {
                        $product->update(['supplier_location' => $label]);
                    }
                }
            });
    }

    public function down(): void
    {
        // No rollback — labels may have been edited manually.
    }
};
