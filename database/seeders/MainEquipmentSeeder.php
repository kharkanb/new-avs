<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $types = DB::table("main_equipment_types")->get();
        $brands = DB::table("brands")->where("equipment_type", "!=", "cell")->get();
        $feeders = DB::table("feeders")->get();
        
        foreach ($types as $type) {
            if ($type->has_brand && $brands->count() > 0) {
                for ($i = 0; $i < 3; $i++) {
                    $brand = $brands->random();
                    $feeder = $feeders->random();
                    
                    DB::table("main_equipments")->insert([
                        "type_id" => $type->id,
                        "scada_code" => str_pad(rand(1000, 9999), 4, "0", STR_PAD_LEFT),
                        "brand_id" => $brand->id,
                        "height" => rand(1, 10),
                        "latitude" => 31.8974 + (rand(-100, 100) / 1000),
                        "longitude" => 54.3676 + (rand(-100, 100) / 1000),
                        "created_at" => now(),
                        "updated_at" => now()
                    ]);
                }
            }
        }
    }
}