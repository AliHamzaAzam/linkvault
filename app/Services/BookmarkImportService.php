<?php

namespace App\Services;

use App\Events\BookmarkCreated;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Support\Str;

class BookmarkImportService
{
    public function import(User $user, string $html): array
    {
        $imported = 0;
        $skipped = 0;
        $currentFolder = null;
        $folderMap = []; // folder name → Collection

        $lines = explode("\n", $html);

        foreach ($lines as $line) {
            $line = trim($line);

            // Detect folder headers: <DT><H3 ...>Folder Name</H3>
            if (preg_match('/<H3[^>]*>(.+?)<\/H3>/i', $line, $m)) {
                $folderName = html_entity_decode(strip_tags($m[1]), ENT_QUOTES, 'UTF-8');

                if (!isset($folderMap[$folderName])) {
                    $folderMap[$folderName] = Collection::firstOrCreate(
                        ['user_id' => $user->id, 'slug' => Str::slug($folderName)],
                        [
                            'user_id' => $user->id, 
                            'name' => $folderName,
                            'color' => $this->generateRandomColor(),
                            'is_public' => false,
                        ]
                    );
                }

                $currentFolder = $folderMap[$folderName];
                continue;
            }

            // Closing folder
            if (str_contains($line, '</DL>')) {
                $currentFolder = null;
                continue;
            }

            // Bookmark entry: <DT><A HREF="url" ...>Title</A>
            if (preg_match('/<A\s+HREF="([^"]+)"[^>]*>(.+?)<\/A>/i', $line, $m)) {
                $url = $m[1];
                $title = html_entity_decode(strip_tags($m[2]), ENT_QUOTES, 'UTF-8');
                
                // Truncate title if too long (max 250 chars, leaving room for null byte)
                $title = mb_substr($title, 0, 250);

                // Skip non-http URLs (javascript:, data:, etc.)
                if (!str_starts_with(strtolower($url), 'http')) {
                    $skipped++;
                    continue;
                }

                // Skip duplicates
                $existing = $user->bookmarks()->where('url', $url)->first();
                if ($existing) {
                    // Still add to collection if applicable
                    if ($currentFolder) {
                        $existing->collections()->syncWithoutDetaching([$currentFolder->id]);
                    }
                    $skipped++;
                    continue;
                }

                // Create bookmark
                $bookmark = $user->bookmarks()->create([
                    'url' => $url,
                    'title' => $title,
                    'is_public' => false,
                ]);

                if ($currentFolder) {
                    $bookmark->collections()->attach($currentFolder->id);
                }

                event(new BookmarkCreated($bookmark));
                $imported++;

                // Safety limit
                if ($imported >= 1000) {
                    break;
                }
            }
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'collections_created' => count($folderMap),
        ];
    }

    /**
     * Generate a random color for new collections.
     */
    private function generateRandomColor(): string
    {
        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'];
        return $colors[array_rand($colors)];
    }
}
