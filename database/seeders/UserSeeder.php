<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table("users")->updateOrInsert(
            ["email" => "admin@avs.com"],
            [
                "name" => "مدیر سیستم",
                "password" => Hash::make("123456"),
                "email_verified_at" => now(),
                "created_at" => now(),
                "updated_at" => now()
            ]
        );
        
        DB::table("users")->updateOrInsert(
            ["email" => "tech@avs.com"],
            [
                "name" => "کارشناس فنی",
                "password" => Hash::make("123456"),
                "email_verified_at" => now(),
                "created_at" => now(),
                "updated_at" => now()
            ]
        );
    }
}