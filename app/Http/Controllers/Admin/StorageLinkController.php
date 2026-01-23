<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class StorageLinkController extends Controller
{
    public function create(Request $request)
    {
        $token = $request->get('token');
        $expectedToken = env('STORAGE_LINK_TOKEN');
        
        if (empty($expectedToken)) {
            $errorMsg = 'STORAGE_LINK_TOKEN is not set in .env file. Please add: STORAGE_LINK_TOKEN=your-secret-token-here';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg
                ], 500);
            }
            return redirect()->route('admin.settings.index')
                ->with('error', $errorMsg);
        }
        
        if (empty($token) || $token !== $expectedToken) {
            $errorMsg = 'Invalid or missing token. Please add ?token=YOUR_TOKEN to the URL. Make sure the token matches STORAGE_LINK_TOKEN in your .env file.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg
                ], 403);
            }
            return redirect()->route('admin.settings.index')
                ->with('error', $errorMsg);
        }

        try {
            $linkPath = public_path('storage');
            
            if (File::exists($linkPath) || is_link($linkPath)) {
                if (is_link($linkPath)) {
                    unlink($linkPath);
                } else {
                    File::deleteDirectory($linkPath);
                }
            }
            
            Artisan::call('storage:link');
            
            $output = Artisan::output();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Storage link created successfully!',
                    'output' => $output
                ]);
            }
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'Storage link created successfully!');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.settings.index')
                ->with('error', 'Error creating storage link: ' . $e->getMessage());
        }
    }
}

