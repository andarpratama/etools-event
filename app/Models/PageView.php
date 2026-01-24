<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'view_date',
        'url',
        'ip_address',
        'user_agent',
        'country',
        'city',
        'region',
        'country_code',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'view_date' => 'date',
    ];

    public static function getDailyViews($days = 30)
    {
        return self::selectRaw('DATE(view_date) as date, COUNT(*) as views')
            ->where('view_date', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->views];
            });
    }

    public static function getTotalViews()
    {
        return self::count();
    }

    public static function getTodayViews()
    {
        return self::whereDate('view_date', today())->count();
    }
}

