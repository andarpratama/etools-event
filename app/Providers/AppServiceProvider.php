<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            View::composer('user-view.*', function ($view) {
                try {
                    $view->with([
                        'settings' => [
                            'logo_light' => Setting::get('logo_light'),
                            'logo_dark' => Setting::get('logo_dark'),
                            'website_name' => Setting::get('website_name', 'ETools Event'),
                            'tagline' => Setting::get('tagline', 'Solusi Lengkap Sewa Alat Event Profesional'),
                            'address' => Setting::get('address'),
                            'contact' => Setting::get('contact'),
                        ]
                    ]);
                } catch (\Exception $e) {
                    // Fallback to defaults if database is not available
                    $view->with([
                        'settings' => [
                            'logo_light' => null,
                            'logo_dark' => null,
                            'website_name' => 'ETools Event',
                            'tagline' => 'Solusi Lengkap Sewa Alat Event Profesional',
                            'address' => null,
                            'contact' => null,
                        ]
                    ]);
                }
            });

            View::composer('admin-view.*', function ($view) {
                try {
                    $view->with([
                        'settings' => [
                            'logo_light' => Setting::get('logo_light'),
                            'logo_dark' => Setting::get('logo_dark'),
                            'website_name' => Setting::get('website_name', 'ETools Event'),
                            'tagline' => Setting::get('tagline'),
                            'address' => Setting::get('address'),
                            'contact' => Setting::get('contact'),
                        ]
                    ]);
                } catch (\Exception $e) {
                    // Fallback to defaults if database is not available
                    $view->with([
                        'settings' => [
                            'logo_light' => null,
                            'logo_dark' => null,
                            'website_name' => 'ETools Event',
                            'tagline' => null,
                            'address' => null,
                            'contact' => null,
                        ]
                    ]);
                }
            });
        } catch (\Exception $e) {
            // Silently fail if database connection is not available
            // This allows the app to boot even if database is not ready
        }
    }
}
