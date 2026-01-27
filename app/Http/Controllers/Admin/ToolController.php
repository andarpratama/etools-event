<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ToolController extends Controller
{
    public function index()
    {
        return view('admin-view.tools.index');
    }

    public function create()
    {
        return view('admin-view.tools.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_order' => 'required|integer|min:1',
            'image_url' => 'nullable|url|max:500',
            'badge_color' => 'required|string|in:primary,warning,success,danger,info,secondary',
            'is_active' => 'nullable|in:0,1,true,false',
            'images' => 'nullable|array',
            'images.*' => 'nullable|url|max:500',
            'image_files' => 'nullable|array',
            'image_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,webm|max:10240',
            'media_types' => 'nullable|array',
            'media_types.*' => 'nullable|in:image,video',
        ]);

        $validated['is_active'] = $request->has('is_active') && ($request->input('is_active') == '1' || $request->input('is_active') === true || $request->input('is_active') === 'true');

        $tool = Tool::create($validated);

        $sortOrder = 0;

        if ($request->hasFile('image_files')) {
            $mediaTypes = $request->input('media_types', []);
            foreach ($request->file('image_files') as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('tools', 'public');
                    $fileUrl = Storage::url($path);
                    $this->syncFileToPublic($path);
                    
                    $mimeType = $file->getMimeType();
                    $type = strpos($mimeType, 'video/') === 0 ? 'video' : 'image';
                    if (isset($mediaTypes[$index])) {
                        $type = $mediaTypes[$index];
                    }
                    
                    ToolImage::create([
                        'tool_id' => $tool->id,
                        'image_url' => $fileUrl,
                        'type' => $type,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        if ($request->has('images') && is_array($request->images)) {
            $mediaTypes = $request->input('media_types', []);
            foreach ($request->images as $index => $imageUrl) {
                if (!empty($imageUrl)) {
                    $type = 'image';
                    $urlIndex = count($request->file('image_files', [])) + $index;
                    if (isset($mediaTypes[$urlIndex])) {
                        $type = $mediaTypes[$urlIndex];
                    }
                    
                    ToolImage::create([
                        'tool_id' => $tool->id,
                        'image_url' => $imageUrl,
                        'type' => $type,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tool created successfully.',
                'tool' => $tool
            ]);
        }

        return redirect()->route('admin.tools.index')->with('success', 'Tool created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    public function show(string $id)
    {
        $tool = Tool::findOrFail($id);
        return view('admin-view.tools.show', compact('tool'));
    }

    public function edit(string $id)
    {
        $tool = Tool::with('images')->findOrFail($id);
        return view('admin-view.tools.edit', compact('tool'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $tool = Tool::findOrFail($id);

            $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'min_order' => 'required|integer|min:1',
            'image_url' => 'nullable|url|max:500',
            'badge_color' => 'required|string|in:primary,warning,success,danger,info,secondary',
            'is_active' => 'nullable|in:0,1,true,false',
            'images' => 'nullable|array',
            'images.*' => 'nullable|url|max:500',
            'image_files' => 'nullable|array',
            'image_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,webm|max:10240',
            'media_types' => 'nullable|array',
            'media_types.*' => 'nullable|in:image,video',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'exists:tool_images,id',
        ]);

        $validated['is_active'] = $request->has('is_active') && ($request->input('is_active') == '1' || $request->input('is_active') === true || $request->input('is_active') === 'true');

        $tool->update($validated);

        if ($request->has('existing_images') && is_array($request->existing_images)) {
            $existingImageIds = array_filter(array_map('intval', $request->existing_images));
            $imagesToDelete = ToolImage::where('tool_id', $tool->id)
                ->whereNotIn('id', $existingImageIds)
                ->get();
            
            foreach ($imagesToDelete as $image) {
                if (strpos($image->image_url, '/storage/') === 0) {
                    $path = str_replace('/storage/', '', $image->image_url);
                    Storage::disk('public')->delete($path);
                    $this->deletePublicFile($path);
                }
                $image->delete();
            }
        } elseif ($request->has('existing_images') && empty($request->existing_images)) {
            // All images were removed
            foreach ($tool->images as $image) {
                if (strpos($image->image_url, '/storage/') === 0) {
                    $path = str_replace('/storage/', '', $image->image_url);
                    Storage::disk('public')->delete($path);
                    $this->deletePublicFile($path);
                }
            }
            $tool->images()->delete();
        }

        $existingCount = $tool->images()->count();
        $sortOrder = $existingCount;

        if ($request->hasFile('image_files')) {
            $mediaTypes = $request->input('media_types', []);
            foreach ($request->file('image_files') as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('tools', 'public');
                    $fileUrl = Storage::url($path);
                    $this->syncFileToPublic($path);
                    
                    $mimeType = $file->getMimeType();
                    $type = strpos($mimeType, 'video/') === 0 ? 'video' : 'image';
                    if (isset($mediaTypes[$index])) {
                        $type = $mediaTypes[$index];
                    }
                    
                    ToolImage::create([
                        'tool_id' => $tool->id,
                        'image_url' => $fileUrl,
                        'type' => $type,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        if ($request->has('images') && is_array($request->images)) {
            $mediaTypes = $request->input('media_types', []);
            foreach ($request->images as $index => $imageUrl) {
                if (!empty($imageUrl)) {
                    $type = 'image';
                    $urlIndex = count($request->file('image_files', [])) + $index;
                    if (isset($mediaTypes[$urlIndex])) {
                        $type = $mediaTypes[$urlIndex];
                    }
                    
                    ToolImage::create([
                        'tool_id' => $tool->id,
                        'image_url' => $imageUrl,
                        'type' => $type,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        if ($request->expectsJson() || $request->ajax()) {
            $tool->refresh();
            $tool->load('images');
            return response()->json([
                'success' => true,
                'message' => 'Tool updated successfully.',
                'tool' => [
                    'id' => $tool->id,
                    'name' => $tool->name,
                    'images' => $tool->images->map(function($image) {
                        return [
                            'id' => $image->id,
                            'image_url' => $image->image_url,
                            'type' => $image->type ?? 'image',
                            'sort_order' => $image->sort_order
                        ];
                    })->values()->all()
                ]
            ]);
        }

        return redirect()->route('admin.tools.index')->with('success', 'Tool updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    public function destroy(string $id)
    {
        $tool = Tool::findOrFail($id);
        $tool->delete();

        return redirect()->route('admin.tools.index')->with('success', 'Tool deleted successfully.');
    }

    public function deleteImage(string $imageId)
    {
        try {
            $image = ToolImage::findOrFail($imageId);
            $toolId = $image->tool_id;
            
            if (strpos($image->image_url, '/storage/') === 0) {
                $path = str_replace('/storage/', '', $image->image_url);
                Storage::disk('public')->delete($path);
                $this->deletePublicFile($path);
            }
            
            $image->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setPrimaryImage(Request $request, string $imageId)
    {
        try {
            $image = ToolImage::findOrFail($imageId);
            $tool = $image->tool;

            // Get current sort order of the image to be set as primary
            $newPrimarySortOrder = $image->sort_order;

            // Get all images for this tool ordered by sort_order
            $allImages = ToolImage::where('tool_id', $tool->id)->orderBy('sort_order')->get();

            // Reorder: set the selected image to 0, and shift others
            $sortOrder = 0;
            foreach ($allImages as $img) {
                if ($img->id == $imageId) {
                    $img->sort_order = 0;
                } else {
                    $sortOrder++;
                    $img->sort_order = $sortOrder;
                }
                $img->save();
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Primary image updated successfully.'
                ]);
            }

            return redirect()->back()->with('success', 'Primary image updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update primary image.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to update primary image.');
        }
    }

    public function datatable()
    {
        $tools = Tool::select(['id', 'name', 'category', 'price', 'is_active', 'created_at']);

        return DataTables::of($tools)
            ->addColumn('action', function ($tool) {
                return '<a href="' . route('admin.tools.edit', $tool->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a> ' .
                       '<button class="btn btn-sm btn-danger" onclick="deleteTool(' . $tool->id . ')"><i class="fas fa-trash"></i></button>';
            })
            ->editColumn('price', function ($tool) {
                return 'Rp ' . number_format($tool->price, 0, ',', '.');
            })
            ->editColumn('is_active', function ($tool) {
                return $tool->is_active 
                    ? '<span class="badge badge-success">Active</span>' 
                    : '<span class="badge badge-secondary">Inactive</span>';
            })
            ->editColumn('created_at', function ($tool) {
                $months = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                
                $date = Carbon::parse($tool->created_at);
                $day = $date->day;
                $month = $months[$date->month];
                $year = $date->year;
                
                return $day . ' ' . $month . ' ' . $year;
            })
            ->rawColumns(['action', 'is_active'])
            ->make(true);
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
