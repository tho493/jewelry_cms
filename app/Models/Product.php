<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'product_code',
        'slug',
        'short_description',
        'description',
        'name_hantu',
        'main_character',
        'form_characteristics',
        'cultural_meaning',
        'price',
        'material',
        'category_id',
        'status',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'price' => 'decimal:0',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(100);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relationships ─────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class)->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Media::class)->where('type', 'image')->orderBy('sort_order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Media::class)->where('type', 'video')->orderBy('sort_order');
    }

    public function audios(): HasMany
    {
        return $this->hasMany(Media::class)->where('type', 'audio')->orderBy('sort_order');
    }

    // ── Accessors ─────────────────────────────────────────

    public function coverImage(): ?Media
    {
        return $this->media()
            ->where('type', 'image')
            ->where('is_cover', true)
            ->first()
            ?? $this->images()->first();
    }

    // ── Scopes ────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
