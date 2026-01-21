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
        View::composer('user-view.*', function ($view) {
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
        });

        View::composer('admin-view.*', function ($view) {
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
        });
    }
}
