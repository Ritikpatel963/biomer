<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Str;

class BlogContentProcessor
{
    public function process(string $html): array
    {
        $cleanHtml = HtmlSanitizer::clean($html);

        if (trim($cleanHtml) === '') {
            return ['html' => '', 'headings' => []];
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previousState = libxml_use_internal_errors(true);
        $loaded = $dom->loadHTML(
            '<?xml encoding="UTF-8"><div id="blog-content-root">' . $cleanHtml . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        if (!$loaded) {
            return ['html' => $cleanHtml, 'headings' => []];
        }

        $xpath = new DOMXPath($dom);
        $root = $xpath->query('//*[@id="blog-content-root"]')->item(0);

        if (!$root instanceof DOMElement) {
            return ['html' => $cleanHtml, 'headings' => []];
        }

        $headings = [];
        $usedIds = [];

        foreach ($xpath->query('.//h2 | .//h3', $root) as $heading) {
            if (!$heading instanceof DOMElement) {
                continue;
            }

            $title = trim(preg_replace('/\s+/', ' ', $heading->textContent));
            if ($title === '') {
                continue;
            }

            $baseId = Str::slug($title) ?: 'section';
            $id = $baseId;
            $suffix = 2;

            while (isset($usedIds[$id])) {
                $id = $baseId . '-' . $suffix++;
            }

            $usedIds[$id] = true;
            $heading->setAttribute('id', $id);
            $headings[] = [
                'id' => $id,
                'title' => $title,
                'level' => strtolower($heading->tagName),
            ];
        }

        $renderedHtml = '';
        foreach ($root->childNodes as $child) {
            $renderedHtml .= $dom->saveHTML($child);
        }

        return ['html' => $renderedHtml, 'headings' => $headings];
    }
}
