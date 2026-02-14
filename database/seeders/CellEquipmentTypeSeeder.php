<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CellEquipmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            "دژنکتور",
            "کلید بار",
            "رله حفاظتی",
            "کنترل پنل",
            "مودم",
            "RTU",
            "باتری",
            "شارژر باتری",
            "CT",
            "PT",
            "فیوز",
            "سکسیونر داخلی",
            "ترانس جریان",
            "ترانس ولتاژ",
            "برد مخابراتی",
        ];

        foreach ($types as $type) {
            DB::table("cell_equipment_types")->updateOrInsert(
                ["name" => $type],
                ["created_at" => now(), "updated_at" => now()]
            );
        }
    }
}