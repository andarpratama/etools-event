<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index()
    {
        $baseUrl = config('app.url');
        $sitemapUrl = $baseUrl . '/sitemap.xml';
        
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /dashboard\n";
        $content .= "Disallow: /api/\n\n";
        $content .= "Sitemap: {$sitemapUrl}\n";
        
        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}

