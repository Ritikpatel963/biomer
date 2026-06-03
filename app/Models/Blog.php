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
        'author',
        'reading_time',
        'description',
        'tags',
        'status',
        'meta_title',
        'meta_tags',
        'meta_description',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $blog) {
            $blog->slug = self::uniqueSlug($blog->title);
        });

        static::updating(function (self $blog) {
            if ($blog->isDirty('title')) {
                $blog->slug = self::uniqueSlug($blog->title, $blog->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug  = Str::slug($title);
        $query = self::where('slug', $slug);
        if ($ignoreId) $query->where('id', '!=', $ignoreId);
        $count = $query->count();
        return $count ? "{$slug}-{$count}" : $slug;
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
}
