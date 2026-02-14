<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsumableItemSeeder extends Seeder
{
    public function run(): void
    {
        $consumables = config("consumables", []);
        
        foreach ($consumables as $item) {
            DB::table("consumable_items")->updateOrInsert(
                ["name" => $item["name"]],
                [
                    "unit" => $item["unit"],
                    "created_at" => now(),
                    "updated_at" => now()
                ]
            );
        }
    }
}