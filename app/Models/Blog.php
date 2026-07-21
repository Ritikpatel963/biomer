<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\BlogReview;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'thumbnail',
        'thumbnail_alt',
        'author',
        'author_bio',
        'reading_time',
        'published_at',
        'description',
        'tags',
        'status',
        'meta_title',
        'meta_tags',
        'meta_description',
        'canonical_url',
        'faq_items',
    ];

    protected $casts = [
        'faq_items' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $blog) {
            $blog->slug = self::uniqueSlug($blog->slug ?: $blog->title);

            if ($blog->status === 'published' && blank($blog->published_at)) {
                $blog->published_at = now();
            }
        });

        static::updating(function (self $blog) {
            if ($blog->isDirty('slug')) {
                $blog->slug = self::uniqueSlug($blog->slug, $blog->id);
            } elseif (blank($blog->slug)) {
                $blog->slug = self::uniqueSlug($blog->title, $blog->id);
            }

            if ($blog->status === 'published' && blank($blog->published_at)) {
                $blog->published_at = now();
            }
        });
    }

    public static function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = substr(Str::slug($value) ?: 'blog-post', 0, 255);
        $slug = $base;
        $suffix = 2;

        while (self::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $ending = '-' . $suffix++;
            $slug = rtrim(substr($base, 0, 255 - strlen($ending)), '-') . $ending;
        }

        return $slug;
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(BlogReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(BlogReview::class)->where('status', 'approved');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('assets/images/user.png');
    }

    public function getThumbnailAltTextAttribute(): string
    {
        return $this->thumbnail_alt ?: $this->title;
    }
}
