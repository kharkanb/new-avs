<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CellEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $mainEquipments = DB::table("main_equipments")->get();
        $cellTypes = DB::table("cell_equipment_types")->get();
        $brands = DB::table("brands")->where("equipment_type", "!=", "main")->get();
        
        foreach ($mainEquipments->take(10) as $equipment) {
            foreach ($cellTypes->take(3) as $type) {
                $brand = $brands->random();
                
                DB::table("cell_equipments")->insert([
                    "main_equipment_id" => $equipment->id,
                    "type_id" => $type->id,
                    "brand_id" => $brand->id,
                    "serial_number" => "SN-" . rand(10000, 99999),
                    "model" => "Model-" . chr(rand(65, 90)),
                    "manufacture_year" => rand(2020, 2025),
                    "created_at" => now(),
                    "updated_at" => now()
                ]);
            }
        }
    }
}