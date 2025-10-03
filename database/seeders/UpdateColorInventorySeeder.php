<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarVariant;

class UpdateColorInventorySeeder extends Seeder
{
    public function run(): void
    {
        // Update color_inventory for all car variants that have colors
        $variants = CarVariant::with('colors')->get();
        
        foreach ($variants as $variant) {
            if ($variant->colors && $variant->colors->count() > 0) {
                $inventory = [];
                foreach ($variant->colors as $color) {
                    // Default inventory: 10 units available per color
                    $inventory[$color->id] = [
                        'quantity' => 10,
                        'reserved' => rand(0, 3), // Random reserved 0-3
                        'available' => 10 - rand(0, 3), // Available = quantity - reserved
                    ];
                }
                
                $variant->update(['color_inventory' => $inventory]);
                
                echo "Updated color inventory for variant: {$variant->name} with " . count($inventory) . " colors\n";
            }
        }
        
        echo "Color inventory update completed!\n";
    }
}
