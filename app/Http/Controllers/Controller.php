<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;  // Add this import

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveImage($image, $path = 'public')
    {
        if (!$image) {
            return null;
        }

        $filename = time() . '.png';

        // Save to storage
        Storage::disk($path)->put($filename, base64_decode($image));

        // Return the path
        // Url is the base URL, e.g., localhost:8000
        return URL::to('/') . '/storage/' . $path . '/' . $filename;
    }
}
