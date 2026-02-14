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
            $type = DB::table("equipment_types")->where("name", $typeName)->first();
            
            if ($type) {
                $index = 1;
                foreach ($items as $item) {
                    DB::table("checklist_templates")->updateOrInsert(
                        [
                            "equipment_type_id" => $type->id,
                            "item_index" => $index
                        ],
                        [
                            "item_text" => $item,
                            "created_at" => now(),
                            "updated_at" => now()
                        ]
                    );
                    $index++;
                }
            }
        }
    }
}