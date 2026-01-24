<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin/*') || $request->is('dashboard') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        if ($request->isMethod('GET') && !$request->ajax() && !$request->wantsJson()) {
            try {
                $url = $request->path();
                $ipAddress = $request->ip();
                $userAgent = $request->userAgent();
                $viewDate = now()->toDateString();
                
                $sessionKey = 'page_view_' . md5($ipAddress . $url . $viewDate);
                $cacheKey = 'pv_' . md5($ipAddress . $url . now()->format('Y-m-d-H-i'));
                
                if (!session()->has($sessionKey) && !Cache::has($cacheKey)) {
                    $location = $this->getLocationFromIP($ipAddress);
                    
                    PageView::create([
                        'view_date' => $viewDate,
                        'url' => $url,
                        'ip_address' => $ipAddress,
                        'user_agent' => $userAgent,
                        'country' => $location['country'] ?? null,
                        'city' => $location['city'] ?? null,
                        'region' => $location['region'] ?? null,
                        'country_code' => $location['country_code'] ?? null,
                        'latitude' => $location['latitude'] ?? null,
                        'longitude' => $location['longitude'] ?? null,
                    ]);
                    
                    session()->put($sessionKey, true);
                    Cache::put($cacheKey, true, now()->addMinutes(5));
                }
            } catch (\Exception $e) {
                Log::warning('Page view tracking failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }

    private function getLocationFromIP($ipAddress)
    {
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1' || str_starts_with($ipAddress, '192.168.') || str_starts_with($ipAddress, '10.')) {
            return [];
        }

        $cacheKey = 'ip_location_' . md5($ipAddress);
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($ipAddress) {
            try {
                $response = @file_get_contents("http://ip-api.com/json/{$ipAddress}?fields=status,message,country,regionName,city,lat,lon,countryCode");
                
                if ($response === false) {
                    return [];
                }
                
                $data = json_decode($response, true);
                
                if ($data && isset($data['status']) && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? null,
                        'city' => $data['city'] ?? null,
                        'region' => $data['regionName'] ?? null,
                        'country_code' => $data['countryCode'] ?? null,
                        'latitude' => $data['lat'] ?? null,
                        'longitude' => $data['lon'] ?? null,
                    ];
                }
            } catch (\Exception $e) {
                Log::warning('IP geolocation failed: ' . $e->getMessage());
            }
            
            return [];
        });
    }
}

