<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeederSeeder extends Seeder
{
    public function run(): void
    {
        $postsData = config("posts_data", []);
        
        foreach ($postsData as $item) {
            $post = DB::table("posts")->where("name", $item["post"])->first();
            
            if ($post) {
                foreach ($item["feeders"] as $feeder) {
                    DB::table("feeders")->updateOrInsert(
                        [
                            "post_id" => $post->id,
                            "name" => $feeder
                        ],
                        ["created_at" => now(), "updated_at" => now()]
                    );
                }
            }
        }
    }
}