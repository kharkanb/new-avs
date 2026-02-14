<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // برندهای کلید و سوییچ
        $brands = [
            ["name" => "NOJA", "category" => "switch"],
            ["name" => "ABB", "category" => "switch"],
            ["name" => "SIEMENS", "category" => "switch"],
            ["name" => "EATON", "category" => "switch"],
            ["name" => "TAVRIDA", "category" => "switch"],
            ["name" => "Schneider", "category" => "switch"],
            ["name" => "LS", "category" => "switch"],
            ["name" => "Hyundai", "category" => "switch"],
            
            // برندهای مودم
            ["name" => "TELTONIKA", "category" => "modem"],
            ["name" => "HUAWEI", "category" => "modem"],
            ["name" => "D-LINK", "category" => "modem"],
            ["name" => "Mikrotik", "category" => "modem"],
            ["name" => "Fortak", "category" => "modem"],
            ["name" => "FSK", "category" => "modem"],
            
            // برندهای RTU
            ["name" => "SICAM", "category" => "rtu"],
            ["name" => "FARAB", "category" => "rtu"],
            ["name" => "ARVAND", "category" => "rtu"],
            
            // برندهای باتری
            ["name" => "Yuasa", "category" => "battery"],
            ["name" => "CSB", "category" => "battery"],
            ["name" => "Vision", "category" => "battery"],
            ["name" => "Rocket", "category" => "battery"],
        ];

        foreach ($brands as $brand) {
            DB::table("brands")->updateOrInsert(
                ["name" => $brand["name"], "category" => $brand["category"]],
                [
                    "created_at" => now(),
                    "updated_at" => now()
                ]
            );
        }
    }
}