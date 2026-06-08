<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('AVS_ADMIN_PASSWORD') ?: Str::password(32);
        $techPassword = env('AVS_TECH_PASSWORD') ?: Str::password(32);

        if (!env('AVS_ADMIN_PASSWORD') || !env('AVS_TECH_PASSWORD')) {
            $this->command?->warn('AVS_ADMIN_PASSWORD/AVS_TECH_PASSWORD are not set; generated random seeded passwords for missing values.');
        }

        DB::table("users")->updateOrInsert(
            ["email" => "admin@avs.com"],
            [
                "name" => "مدیر سیستم",
                "password" => Hash::make($adminPassword),
                "role" => "admin",
                "email_verified_at" => now(),
                "created_at" => now(),
                "updated_at" => now()
            ]
        );
        
        DB::table("users")->updateOrInsert(
            ["email" => "tech@avs.com"],
            [
                "name" => "کارشناس فنی",
                "password" => Hash::make($techPassword),
                "role" => "tech",
                "email_verified_at" => now(),
                "created_at" => now(),
                "updated_at" => now()
            ]
        );
    }
}