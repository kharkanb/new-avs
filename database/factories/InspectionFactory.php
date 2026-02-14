<?php

namespace Database\Factories;

use App\Models\Inspection;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionFactory extends Factory
{
    protected $model = Inspection::class;

    public function definition(): array
    {
        return [
            'inspection_date' => $this->faker->date(),
            'daily_start_time' => $this->faker->time(),
            'daily_end_time' => $this->faker->time(),
            'contractor' => $this->faker->company(),
            'contract_coefficient' => $this->faker->randomFloat(2, 1, 2),
            'contract_number' => $this->faker->numerify('CON-#####'),
            'whatsapp_number' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['draft', 'completed', 'archived']),
        ];
    }
}