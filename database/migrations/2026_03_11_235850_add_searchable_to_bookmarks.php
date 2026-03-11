<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL: Use tsvector with GIN index for full-text search
            DB::statement(<<<'SQL'
                ALTER TABLE bookmarks 
                ADD COLUMN searchable tsvector 
                GENERATED ALWAYS AS (
                    to_tsvector('english', 
                        coalesce(title, '') || ' ' || 
                        coalesce(description, '') || ' ' || 
                        coalesce(site_name, '') || ' ' || 
                        url
                    )
                ) STORED
            SQL);

            DB::statement(<<<'SQL'
                CREATE INDEX bookmarks_searchable_idx ON bookmarks USING GIN(searchable)
            SQL);
        } else {
            // SQLite/MySQL: Add a simple text column for basic search
            Schema::table('bookmarks', function (Blueprint $table) {
                $table->text('searchable')->nullable();
            });

            // Create a regular index for the searchable column
            Schema::table('bookmarks', function (Blueprint $table) {
                $table->index('searchable');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS bookmarks_searchable_idx');
        }

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropColumn('searchable');
        });
    }
};
