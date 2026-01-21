<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = config('app.url');
        
        $urls = [
            [
                'loc' => $baseUrl,
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '1.0'
            ]
        ];
        
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('tools')) {
                $tools = Tool::where('is_active', true)->get();
                foreach ($tools as $tool) {
                    $urls[] = [
                        'loc' => $baseUrl . '/#tools',
                        'lastmod' => $tool->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority' => '0.8'
                    ];
                }
            }
        } catch (\Exception $e) {
        }
        
        $xml = view('sitemap', ['urls' => $urls])->render();
        
        return response($xml, 200)
            ->header('Content-Type', 'text/xml; charset=utf-8');
    }
}

