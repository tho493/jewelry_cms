<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'file_path',
        'thumbnail_path',
        'alt_text',
        'caption',
        'is_cover',
        'sort_order',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['url', 'thumbnail_url'];

    // ── Relationships ─────────────────────────────────────

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── Accessors ─────────────────────────────────────────

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            return Storage::disk('public')->url($this->thumbnail_path);
        }

        if ($this->type === 'image') {
            return $this->url;
        }

        return null;
    }
}
