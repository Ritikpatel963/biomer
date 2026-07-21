<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SafeImageUpload
{
    public const VALIDATION_RULE = 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240';

    private const MIME_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    public static function storePublic(UploadedFile $file, string $directory): string
    {
        if (!$file->isValid()) {
            throw ValidationException::withMessages([
                'image' => 'The uploaded image is invalid.',
            ]);
        }

        $imageInfo = @getimagesize($file->getRealPath());
        $mimeType = $imageInfo['mime'] ?? $file->getMimeType();

        if (!array_key_exists($mimeType, self::MIME_EXTENSIONS)) {
            throw ValidationException::withMessages([
                'image' => 'Only JPG, PNG, WebP, and GIF images are allowed.',
            ]);
        }

        if (!$imageInfo || empty($imageInfo[0]) || empty($imageInfo[1])) {
            throw ValidationException::withMessages([
                'image' => 'The uploaded image content is invalid.',
            ]);
        }

        $filename = (string) Str::uuid() . '.' . self::MIME_EXTENSIONS[$mimeType];

        $path = Storage::disk('public')->putFileAs($directory, $file, $filename);

        if (!$path) {
            throw new \RuntimeException('Unable to store uploaded image.');
        }

        return $path;
    }
}
