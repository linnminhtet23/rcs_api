<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public static function save($file, $location)
    {
        $now = now();
        $filename = $now->toDateString() . '_' . $now->timestamp . '_' . $file->getClientOriginalName();
        $file->storeAs("public/" . $location, $filename);
        return $filename;
    }
    public static function remove($filename, $location)
    {
        if ($filename) {
            Storage::disk('public')->delete($location . '/' . $filename);
        }
    }
}
