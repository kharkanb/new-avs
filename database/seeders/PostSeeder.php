<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $postsData = config("posts_data", []);
        
        foreach ($postsData as $item) {
            DB::table("posts")->updateOrInsert(
                ["name" => $item["post"]],
                ["created_at" => now(), "updated_at" => now()]
            );
        }
    }
}