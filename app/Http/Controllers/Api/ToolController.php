<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ToolController extends Controller
{
    public function index()
    {
        try {
            if (!Schema::hasTable('tools')) {
                return response()->json([]);
            }
            $tools = Tool::with('images')->where('is_active', true)->get();
            return response()->json($tools);
        } catch (\Exception $e) {
            Log::error('Tools API Error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
