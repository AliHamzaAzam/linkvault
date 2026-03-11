<?php

namespace App\Listeners;

use App\Events\BookmarkCreated;
use App\Jobs\ScrapeBookmarkMetadata;

class DispatchMetadataScrape
{
    /**
     * Handle the event.
     *
     * @param BookmarkCreated $event
     * @return void
     */
    public function handle(BookmarkCreated $event): void
    {
        ScrapeBookmarkMetadata::dispatch($event->bookmark);
    }
}
