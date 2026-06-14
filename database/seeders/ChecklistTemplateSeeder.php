<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChecklistTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $checklists = config("equipment_checklists", []);
        
        foreach ($checklists as $typeName => $items) {
            DB::table("main_equipment_types")->updateOrInsert(
                ["name" => $typeName],
                [
                    "feeder_mode" => in_array($typeName, [
                        "پست دو سو تغذیه (مشترک حساس)",
                        "پست دو سو تغذیه (بیمارستانی)",
                    ], true) ? "dual" : "single",
                    "has_cells" => in_array($typeName, [
                        "پست دو سو تغذیه (مشترک حساس)",
                        "پست دو سو تغذیه (بیمارستانی)",
                        "مشترک ولتاژ اولیه",
                    ], true),
                    "has_brand" => in_array($typeName, [
                        "ریکلوزر",
                        "سکسیونر",
                        "سکشنالایزر",
                        "فالت دتکتور",
                    ], true),
                    "has_height" => !in_array($typeName, [
                        "پست دو سو تغذیه (مشترک حساس)",
                        "پست دو سو تغذیه (بیمارستانی)",
                        "مشترک ولتاژ اولیه",
                    ], true),
                    "created_at" => now(),
                    "updated_at" => now(),
                ]
            );

            $type = DB::table("main_equipment_types")->where("name", $typeName)->first();

            DB::table("checklist_templates")->updateOrInsert(
                ["main_equipment_type_id" => $type->id],
                [
                    "title" => "چک‌لیست {$typeName}",
                    "description" => null,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]
            );

            $template = DB::table("checklist_templates")
                ->where("main_equipment_type_id", $type->id)
                ->first();

            foreach (array_values($items) as $index => $item) {
                DB::table("checklist_template_items")->updateOrInsert(
                    [
                        "checklist_template_id" => $template->id,
                        "item_index" => $index + 1,
                    ],
                    [
                        "item_text" => $item,
                        "required_level" => "mandatory",
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );
            }
        }
    }
}