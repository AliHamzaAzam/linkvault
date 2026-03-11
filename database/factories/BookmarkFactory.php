<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookmarkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'url' => fake()->url(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'favicon_url' => 'https://www.google.com/s2/favicons?domain=' . fake()->domainName(),
            'og_image_url' => fake()->imageUrl(1200, 630),
            'site_name' => fake()->company(),
            'is_public' => fake()->boolean(30), // 30% public
            'is_archived' => false,
            'meta_scraped_at' => now(),
        ];
    }

    public function unscraped(): static
    {
        return $this->state(fn () => [
            'title' => null,
            'description' => null,
            'favicon_url' => null,
            'og_image_url' => null,
            'site_name' => null,
            'meta_scraped_at' => null,
        ]);
    }

    public function public(): static
    {
        return $this->state(fn () => ['is_public' => true]);
    }

    public function archived(): static
    {
        return $this->state(fn () => ['is_archived' => true]);
    }
}