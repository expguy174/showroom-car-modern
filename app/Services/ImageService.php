<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    public static function uploadImage(UploadedFile $file, $path = 'images')
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $fullPath = $path . '/' . $filename;

        // Store original image
        $file->storeAs($path, $filename, 'public');

        return $fullPath;
    }

    public static function deleteImage($path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public static function getImageUrl($path)
    {
        if (!$path) return null;
        return asset('storage/' . $path);
    }

    public static function optimizeImage($file)
    {
        // Simple optimization - just store the file
        return self::uploadImage($file);
    }
} 