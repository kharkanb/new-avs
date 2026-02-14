<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BaseDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MainEquipmentTypeSeeder::class,
            CellEquipmentTypeSeeder::class,
            BrandSeeder::class,
            DepartmentSeeder::class,
            PostSeeder::class,
            FeederSeeder::class,
            ActivityPriceSeeder::class,
            ConsumableSeeder::class,
            ChecklistTemplateSeeder::class,
            MainEquipmentSeeder::class,
            CellEquipmentSeeder::class,
            UserSeeder::class,
        ]);
    }
}