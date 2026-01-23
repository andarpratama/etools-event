<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if (file_exists($sourcePath)) {
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            copy($sourcePath, $publicPath);
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
