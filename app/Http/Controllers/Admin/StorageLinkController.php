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
            $targetPath = storage_path('app/public');
            
            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }
            
            if (File::exists($linkPath)) {
                if (is_link($linkPath)) {
                    unlink($linkPath);
                } else {
                    File::deleteDirectory($linkPath);
                }
            }
            
            if (function_exists('symlink')) {
                try {
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
                    if (str_contains($e->getMessage(), 'symlink') || str_contains($e->getMessage(), 'Symlink')) {
                        return $this->createStorageCopy($linkPath, $targetPath, $request);
                    }
                    throw $e;
                }
            } else {
                return $this->createStorageCopy($linkPath, $targetPath, $request);
            }
                
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
    
    private function createStorageCopy($linkPath, $targetPath, $request)
    {
        try {
            $publicBaseDir = public_path();
            
            // Check if public directory is writable
            if (!File::isWritable($publicBaseDir)) {
                $errorMsg = 'Public directory is not writable. Please set permissions to 755 or 775 for: ' . $publicBaseDir;
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg,
                        'instruction' => 'Please create the directory manually via File Manager: ' . $linkPath
                    ], 500);
                }
                return redirect()->route('admin.settings.index')
                    ->with('error', $errorMsg . ' Or create the directory manually: ' . $linkPath);
            }
            
            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }
            
            if (File::exists($linkPath)) {
                if (is_link($linkPath)) {
                    @unlink($linkPath);
                } else {
                    // Don't delete if it's a directory with files, just use it
                    if (!File::isDirectory($linkPath) || count(File::allFiles($linkPath)) == 0) {
                        @File::deleteDirectory($linkPath);
                    }
                }
            }
            
            // Create directory recursively
            if (!File::exists($linkPath)) {
                try {
                    File::makeDirectory($linkPath, 0755, true);
                } catch (\Exception $e) {
                    if (str_contains($e->getMessage(), 'File exists') || str_contains($e->getMessage(), 'already exists')) {
                        // Directory already exists, that's fine
                    } else {
                        $errorMsg = 'Cannot create directory: ' . $linkPath . '. Error: ' . $e->getMessage() . '. Please create it manually via File Manager with permissions 755.';
                        if ($request->expectsJson()) {
                            return response()->json([
                                'success' => false,
                                'message' => $errorMsg
                            ], 500);
                        }
                        return redirect()->route('admin.settings.index')
                            ->with('error', $errorMsg);
                    }
                }
            }
            
            if (!is_dir($linkPath)) {
                $errorMsg = 'Directory does not exist and could not be created: ' . $linkPath . '. Please create it manually via File Manager.';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg
                    ], 500);
                }
                return redirect()->route('admin.settings.index')
                    ->with('error', $errorMsg);
            }
            
            File::copyDirectory($targetPath, $linkPath);
            
            $message = 'Storage directory copied successfully! (Symlink not available on this server, using copy method)';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'note' => 'Note: Files are copied, not symlinked. New uploads will be automatically synced.'
                ]);
            }
            
            return redirect()->route('admin.settings.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            $errorMsg = 'Error copying storage: ' . $e->getMessage();
            if (str_contains($e->getMessage(), 'No such file') || str_contains($e->getMessage(), 'mkdir')) {
                $errorMsg .= '. Please create the directory manually: ' . $linkPath . ' via File Manager with permissions 755.';
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg
                ], 500);
            }
            
            return redirect()->route('admin.settings.index')
                ->with('error', $errorMsg);
        }
    }
}

