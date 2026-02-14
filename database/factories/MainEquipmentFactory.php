<?php

namespace Database\Factories;

use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\Post;
use App\Models\Inspection;
use Illuminate\Database\Eloquent\Factories\Factory;

class MainEquipmentFactory extends Factory
{
    protected $model = MainEquipment::class;

    public function definition(): array
    {
   return [
            'inspection_id' => Inspection::factory(),  // ✅ این خط رو اضافه کن
            'main_equipment_type_id' => MainEquipmentType::factory(),  // ✅ این خط رو اضافه کن
            'scada_code' => $this->faker->unique()->numerify('####'),
            'post_id' => Post::factory(),
            'latitude' => $this->faker->latitude(30, 32),
            'longitude' => $this->faker->longitude(54, 56),
            'height' => $this->faker->numberBetween(1, 10),
            'installation_type' => $this->faker->randomElement(['هوایی', 'زمینی', 'داخلی']),
        ];
    }
}