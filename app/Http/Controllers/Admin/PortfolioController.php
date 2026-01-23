<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('admin-view.portfolios.index');
    }

    public function create()
    {
        return view('admin-view.portfolios.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'image_url' => 'required|url|max:500',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);

            $validated['is_active'] = $request->has('is_active') && ($request->input('is_active') == '1' || $request->input('is_active') === true || $request->input('is_active') === 'true');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            $portfolio = Portfolio::create($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Portfolio created successfully.',
                    'portfolio' => $portfolio
                ]);
            }

            return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio created successfully.');
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
        $portfolio = Portfolio::findOrFail($id);
        return view('admin-view.portfolios.show', compact('portfolio'));
    }

    public function edit(string $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        return view('admin-view.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $portfolio = Portfolio::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'image_url' => 'required|url|max:500',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);

            $validated['is_active'] = $request->has('is_active') && ($request->input('is_active') == '1' || $request->input('is_active') === true || $request->input('is_active') === 'true');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            $portfolio->update($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Portfolio updated successfully.',
                    'portfolio' => $portfolio
                ]);
            }

            return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio updated successfully.');
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
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio deleted successfully.');
    }

    public function datatable()
    {
        $portfolios = Portfolio::select(['id', 'title', 'category', 'sort_order', 'is_active', 'created_at']);

        return DataTables::of($portfolios)
            ->addColumn('action', function ($portfolio) {
                return '<a href="' . route('admin.portfolios.edit', $portfolio->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a> ' .
                       '<button class="btn btn-sm btn-danger" onclick="deletePortfolio(' . $portfolio->id . ')"><i class="fas fa-trash"></i></button>';
            })
            ->editColumn('is_active', function ($portfolio) {
                return $portfolio->is_active 
                    ? '<span class="badge badge-success">Active</span>' 
                    : '<span class="badge badge-secondary">Inactive</span>';
            })
            ->editColumn('created_at', function ($portfolio) {
                $months = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                
                $date = Carbon::parse($portfolio->created_at);
                $day = $date->day;
                $month = $months[$date->month];
                $year = $date->year;
                
                return $day . ' ' . $month . ' ' . $year;
            })
            ->rawColumns(['action', 'is_active'])
            ->make(true);
    }
}
