<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogThumbnailTest extends TestCase
{
    public function test_thumbnail_url_points_to_the_public_storage_path(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('blogs/example.png', 'image-content');

        $blog = new Blog([
            'title' => 'Example',
            'thumbnail' => 'blogs/example.png',
        ]);

        $this->assertSame(
            asset('storage/blogs/example.png'),
            $blog->thumbnail_url
        );
        $this->assertTrue(Storage::disk('public')->exists($blog->thumbnail));
    }

    public function test_blog_without_thumbnail_uses_the_fallback_image(): void
    {
        $blog = new Blog(['title' => 'Example']);

        $this->assertSame(asset('assets/images/user.png'), $blog->thumbnail_url);
    }
}
