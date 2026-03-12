<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'position',
        'user_id',
        'share_token',
    ];

    // Auto-generate slug from name
    protected static function booted(): void
    {
        static::creating(function (Collection $collection) {
            if (empty($collection->slug)) {
                $collection->slug = Str::slug($collection->name);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Bookmark::class)
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    /**
     * Generate a new share token for this collection.
     */
    public function generateShareToken(): string
    {
        $token = Str::random(32);
        $this->update(['share_token' => $token]);
        return $token;
    }

    /**
     * Revoke the share token for this collection.
     */
    public function revokeShareToken(): void
    {
        $this->update(['share_token' => null]);
    }

    /**
     * Check if this collection has an active share token.
     */
    public function isShared(): bool
    {
        return $this->share_token !== null;
    }

    /**
     * Get the share URL for this collection.
     */
    public function getShareUrl(): ?string
    {
        if (!$this->share_token) {
            return null;
        }
        return url("/share/{$this->share_token}");
    }
}
