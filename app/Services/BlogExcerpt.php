<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

class BlogExcerpt
{
    public static function render(?string $html, int $limit = 140): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        @$document->loadHTML(
            '<?xml encoding="UTF-8"><div id="blog-excerpt-root">' . HtmlSanitizer::clean($html) . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $root = $document->getElementById('blog-excerpt-root');
        if (! $root) {
            return '';
        }

        $tokens = [];
        self::collectTextTokens($root, $tokens);

        $remaining = max(0, $limit);
        $output = [];
        $wasTruncated = false;

        foreach ($tokens as $token) {
            $text = preg_replace('/\s+/u', ' ', trim($token['text']));
            if ($text === '') {
                continue;
            }

            $separator = $output === [] ? '' : ' ';
            $available = $remaining - mb_strlen($separator);
            if ($available <= 0) {
                $wasTruncated = true;
                break;
            }

            $chunk = mb_substr($text, 0, $available);
            $wasTruncated = $wasTruncated || mb_strlen($text) > mb_strlen($chunk);
            $escaped = e($chunk);

            if ($token['link'] instanceof DOMElement) {
                $href = $token['link']->getAttribute('href');
                $target = $token['link']->getAttribute('target') === '_blank' ? '_blank' : '_self';
                $rel = $target === '_blank' ? ' rel="noopener noreferrer"' : '';
                $escaped = '<a class="blog-excerpt-link" href="' . e($href) . '" target="' . $target . '"' . $rel . '>' . $escaped . '</a>';
            }

            $output[] = $escaped;
            $remaining -= mb_strlen($separator . $chunk);

            if ($wasTruncated || $remaining <= 0) {
                break;
            }
        }

        return implode(' ', $output) . ($wasTruncated ? '&hellip;' : '');
    }

    private static function collectTextTokens(DOMNode $node, array &$tokens, ?DOMElement $link = null): void
    {
        if ($node instanceof DOMElement && strtolower($node->tagName) === 'a') {
            $link = $node;
        }

        if ($node->nodeType === XML_TEXT_NODE) {
            $tokens[] = ['text' => $node->nodeValue ?? '', 'link' => $link];
            return;
        }

        foreach ($node->childNodes as $child) {
            self::collectTextTokens($child, $tokens, $link);
        }
    }
}
