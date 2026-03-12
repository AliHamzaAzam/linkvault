<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create a demo user
        $user = User::factory()->create([
            'name' => 'Ali Hamza',
            'email' => 'ali@linkvault.dev',
            'password' => bcrypt('password'),
        ]);

        // Create tags for this user
        $tags = collect([
            'laravel', 'react', 'ai', 'design', 'devops',
            'tutorial', 'tool', 'api', 'database', 'frontend',
        ])->map(fn ($name) => Tag::create([
            'user_id' => $user->id,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
        ]));

        // Create collections
        $collections = collect([
            ['name' => 'Design Inspiration', 'color' => '#8B5CF6'],
            ['name' => 'Dev Tools', 'color' => '#10B981'],
            ['name' => 'Articles to Read', 'color' => '#F59E0B'],
            ['name' => 'Tutorials', 'color' => '#3B82F6'],
        ])->map(fn ($data, $i) => Collection::factory()->create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'color' => $data['color'],
            'position' => $i,
            'slug' => \Illuminate\Support\Str::slug($data['name']),
        ]));

        // Add a share token to the first collection for demo
        $collections->first()->generateShareToken();

        // Create 25 bookmarks with random tags and collections
        Bookmark::factory()
            ->count(25)
            ->create(['user_id' => $user->id])
            ->each(function (Bookmark $bookmark) use ($tags, $collections) {
                // Attach 1-3 random tags
                $bookmark->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')
                );

                // Attach to 0-2 random collections
                if (rand(0, 1)) {
                    $bookmark->collections()->attach(
                        $collections->random(rand(1, 2))->pluck('id')
                    );
                }
            });
    }
}
