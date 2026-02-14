<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainEquipmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                "name" => "ریکلوزر",
                "feeder_mode" => "single",
                "has_height" => true,
                "has_brand" => true
            ],
            [
                "name" => "سکسیونر",
                "feeder_mode" => "dual",
                "has_height" => true,
                "has_brand" => true
            ],
            [
                "name" => "سکشنالایزر",
                "feeder_mode" => "dual",
                "has_height" => true,
                "has_brand" => true
            ],
            [
                "name" => "فالت دتکتور",
                "feeder_mode" => "single",
                "has_height" => false,
                "has_brand" => true
            ],
            [
                "name" => "پست دو سو تغذیه (مشترک حساس)",
                "feeder_mode" => "dual",
                "has_height" => false,
                "has_brand" => false
            ],
            [
                "name" => "پست دو سو تغذیه (بیمارستانی)",
                "feeder_mode" => "dual",
                "has_height" => false,
                "has_brand" => false
            ],
            [
                "name" => "مشترک ولتاژ اولیه",
                "feeder_mode" => "single",
                "has_height" => false,
                "has_brand" => false
            ]
        ];

        foreach ($types as $type) {
            DB::table("main_equipment_types")->updateOrInsert(
                ["name" => $type["name"]],
                $type
            );
        }
    }
}