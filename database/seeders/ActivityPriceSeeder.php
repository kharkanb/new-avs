<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityPriceSeeder extends Seeder
{
    public function run(): void
    {
        $activities = config("activity_prices", []);
        
        foreach ($activities as $activity) {
            DB::table("activity_prices")->updateOrInsert(  // تغییر نام جدول
                ["code" => $activity["code"]],
                [
                    "title" => $activity["title"],
                    "unit" => $activity["unit"],
                    "unit_price" => $activity["price"],  // توجه: در migration نام ستون unit_price است
                    "created_at" => now(),
                    "updated_at" => now()
                ]
            );
        }
    }
}