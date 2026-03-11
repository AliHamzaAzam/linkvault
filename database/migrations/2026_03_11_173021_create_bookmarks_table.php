<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('url');
            $table->string('title')->nullable();       // NULL until scraped
            $table->text('description')->nullable();
            $table->text('favicon_url')->nullable();
            $table->text('og_image_url')->nullable();
            $table->string('site_name')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->timestamp('meta_scraped_at')->nullable(); // tracks scrape status
            $table->timestamps();

            // Prevent duplicate URLs per user
            $table->unique(['user_id', 'url']);
            // Filter queries
            $table->index(['user_id', 'is_public']);
            $table->index(['user_id', 'is_archived']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
