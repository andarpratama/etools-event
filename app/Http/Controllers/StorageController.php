<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageController extends Controller
{
    public function show($path)
    {
        $filePath = storage_path('app/public/' . $path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }
        
        $mimeType = mime_content_type($filePath);
        $fileName = basename($filePath);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }
}

