<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Media
{
    /** Absolute URL for a stored file, so the React app can use it cross-origin. */
    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url(Storage::disk('public')->url($path));
    }

    /** Stores an upload and removes the file it replaces. */
    public static function store(?UploadedFile $file, string $folder, ?string $previous = null): ?string
    {
        if (! $file) {
            return $previous;
        }

        static::delete($previous);

        return $file->store($folder, 'public');
    }

    public static function delete(?string $path): void
    {
        if (filled($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
