<?php

namespace Database\Factories;

use App\Models\MainEquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MainEquipmentTypeFactory extends Factory
{
    protected $model = MainEquipmentType::class;

    public function definition(): array
    {
        $types = [
            'ریکلوزر',
            'سکسیونر',
            'سکشنالایزر',
            'فالت دتکتور',
            'پست دو سو تغذیه (مشترک حساس)',
            'پست دو سو تغذیه (بیمارستانی)',
            'مشترک ولتاژ اولیه'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($types),
            'feeder_mode' => $this->faker->randomElement(['single', 'dual']),
            'has_cells' => $this->faker->boolean(),
            'has_brand' => $this->faker->boolean(),
            'has_height' => $this->faker->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * تنظیم برای نوعی که سلول دارد
     */
    public function hasCells(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_cells' => true,
        ]);
    }

    /**
     * تنظیم برای نوعی که برند دارد
     */
    public function hasBrand(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_brand' => true,
        ]);
    }
}