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
        // Drop is_public from bookmarks (drop index first)
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_public']);
            $table->dropColumn('is_public');
        });

        // Drop is_public from collections
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });

        // Drop username from users (drop unique index first)
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore is_public on bookmarks
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('site_name');
            $table->index(['user_id', 'is_public']);
        });

        // Restore is_public on collections
        Schema::table('collections', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('color');
        });

        // Restore username on users
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });
    }
};
