<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    private static array $tags = [
        'laravel', 'react', 'typescript', 'ai', 'design',
        'devops', 'tutorial', 'reference', 'tool', 'api',
        'database', 'frontend', 'backend', 'security', 'performance',
    ];

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->unique()->randomElement(self::$tags),
        ];
    }
}