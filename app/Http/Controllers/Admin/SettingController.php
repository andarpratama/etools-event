<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'logo_light' => Setting::get('logo_light'),
            'logo_dark' => Setting::get('logo_dark'),
            'website_name' => Setting::get('website_name'),
            'tagline' => Setting::get('tagline'),
            'address' => Setting::get('address'),
            'contact' => Setting::get('contact'),
        ];

        return view('admin-view.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'logo_light' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'website_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:1000',
            'contact' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('logo_light')) {
            $oldLogo = Setting::get('logo_light');
            if ($oldLogo && strpos($oldLogo, '/storage/') === 0) {
                $path = str_replace('/storage/', '', $oldLogo);
                Storage::disk('public')->delete($path);
                $this->deletePublicFile($path);
            }

            $path = $request->file('logo_light')->store('settings', 'public');
            Setting::set('logo_light', Storage::url($path));
            $this->syncFileToPublic($path);
        }

        if ($request->hasFile('logo_dark')) {
            $oldLogo = Setting::get('logo_dark');
            if ($oldLogo && strpos($oldLogo, '/storage/') === 0) {
                $path = str_replace('/storage/', '', $oldLogo);
                Storage::disk('public')->delete($path);
                $this->deletePublicFile($path);
            }

            $path = $request->file('logo_dark')->store('settings', 'public');
            Setting::set('logo_dark', Storage::url($path));
            $this->syncFileToPublic($path);
        }

        Setting::set('website_name', $validated['website_name']);
        Setting::set('tagline', $validated['tagline'] ?? '');
        Setting::set('address', $validated['address'] ?? '');
        Setting::set('contact', $validated['contact'] ?? '');

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }

    private function syncFileToPublic($storagePath)
    {
        $sourcePath = storage_path('app/public/' . $storagePath);
        $publicPath = public_path('storage/' . $storagePath);
        $publicDir = dirname($publicPath);
        $baseStorageDir = public_path('storage');
        $publicBaseDir = public_path();

        if (!File::exists($sourcePath)) {
            \Log::warning('Source file does not exist: ' . $sourcePath);
            return;
        }

        try {
            // Check if public directory is writable
            if (!File::isWritable($publicBaseDir)) {
                \Log::error('Public directory is not writable: ' . $publicBaseDir);
                return;
            }

            // Create directories recursively, one level at a time if needed
            $directories = [];
            $currentDir = $publicDir;
            while ($currentDir !== $publicBaseDir && $currentDir !== dirname($currentDir)) {
                if (!File::exists($currentDir)) {
                    $directories[] = $currentDir;
                }
                $currentDir = dirname($currentDir);
            }
            $directories = array_reverse($directories);

            foreach ($directories as $dir) {
                if (!File::exists($dir)) {
                    try {
                        File::makeDirectory($dir, 0755, true);
                    } catch (\Exception $e) {
                        \Log::error('Failed to create directory: ' . $dir . ' - ' . $e->getMessage());
                        return;
                    }
                }
            }

            // Verify directories exist and are writable
            if (!File::exists($publicDir)) {
                \Log::error('Failed to create directory: ' . $publicDir);
                return;
            }

            if (!File::isWritable($publicDir)) {
                \Log::error('Directory is not writable: ' . $publicDir);
                return;
            }

            // Copy the file
            File::copy($sourcePath, $publicPath);

            // Verify file was copied
            if (!File::exists($publicPath)) {
                \Log::error('File copy failed - destination does not exist: ' . $publicPath);
            } else {
                \Log::info('File synced successfully', [
                    'source' => $sourcePath,
                    'destination' => $publicPath
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to sync file to public: ' . $e->getMessage(), [
                'source' => $sourcePath,
                'destination' => $publicPath,
                'public_dir' => $publicDir,
                'base_storage_dir' => $baseStorageDir,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function deletePublicFile($path)
    {
        $publicPath = public_path('storage/' . $path);
        if (file_exists($publicPath)) {
            unlink($publicPath);
        }
    }
}
