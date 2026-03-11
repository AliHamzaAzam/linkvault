<?php

namespace App\Jobs;

use App\Models\Bookmark;
use App\Services\MetadataScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeBookmarkMetadata implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The bookmark instance.
     *
     * @var Bookmark
     */
    public Bookmark $bookmark;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array<int, int>
     */
    public $backoff = [10, 60, 300];

    /**
     * Create a new job instance.
     *
     * @param Bookmark $bookmark
     * @return void
     */
    public function __construct(Bookmark $bookmark)
    {
        $this->bookmark = $bookmark;
    }

    /**
     * Execute the job.
     *
     * @param MetadataScraperService $scraper
     * @return void
     */
    public function handle(MetadataScraperService $scraper): void
    {
        $metadata = $scraper->scrape($this->bookmark->url);

        // User-provided data takes priority (use ?? operator)
        $this->bookmark->update([
            'title' => $this->bookmark->title ?? $metadata['title'],
            'description' => $this->bookmark->description ?? $metadata['description'],
            'og_image_url' => $metadata['og_image_url'],
            'site_name' => $metadata['site_name'],
            'favicon_url' => $metadata['favicon_url'],
            'meta_scraped_at' => now(),
        ]);
    }
}
