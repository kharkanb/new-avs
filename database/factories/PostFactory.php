<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $posts = [
            'پست 63 امامزاده جعفر',
            'پست 63 گچساران',
            'پست 63 دوگنبدان',
            'پست 63 باشت',
            'پست 63 لیشتر',
            'پست 63 چرام',
            'پست 63 سوق',
            'پست 63 دهدشت',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($posts),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}