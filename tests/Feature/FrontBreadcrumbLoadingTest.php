<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class FrontBreadcrumbLoadingTest extends TestCase
{
    public function test_default_hero_uses_an_eager_high_priority_optimized_image(): void
    {
        $html = Blade::render('<x-front-breadcrumb title="Contact Us" />');

        $this->assertStringContainsString('breadcumb-img.webp', $html);
        $this->assertStringContainsString('class="fbreadcrumb__background"', $html);
        $this->assertStringContainsString('loading="eager"', $html);
        $this->assertStringContainsString('fetchpriority="high"', $html);
        $this->assertStringContainsString('width="2138"', $html);
        $this->assertStringContainsString('height="736"', $html);
    }
}
