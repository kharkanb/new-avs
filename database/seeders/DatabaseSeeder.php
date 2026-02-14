<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            //EquipmentTypeSeeder::class,
            CellEquipmentTypeSeeder::class,
            BrandSeeder::class,
            DepartmentSeeder::class,
            PostSeeder::class,
            FeederSeeder::class,
            ActivityPriceSeeder::class,
            ConsumableItemSeeder::class,
            ChecklistTemplateSeeder::class,
            UserSeeder::class,
        ]);
    }
}