<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
{
    private static array $collections = [
        ['name' => 'Design Inspiration', 'color' => '#8B5CF6'],
        ['name' => 'Dev Tools', 'color' => '#10B981'],
        ['name' => 'Articles to Read', 'color' => '#F59E0B'],
        ['name' => 'Tutorials', 'color' => '#3B82F6'],
        ['name' => 'Research', 'color' => '#EF4444'],
        ['name' => 'Side Projects', 'color' => '#EC4899'],
    ];

    public function definition(): array
    {
        $collection = fake()->randomElement(self::$collections);

        return [
            'user_id' => User::factory(),
            'name' => $collection['name'],
            'description' => fake()->sentence(),
            'color' => $collection['color'],
            'position' => 0,
        ];
    }
}
