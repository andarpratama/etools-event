<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get($key, $default = null)
    {
        try {
            if (!Schema::hasTable('settings')) {
                return $default;
            }
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            Log::warning('Settings::get() error: ' . $e->getMessage());
            return $default;
        }
    }

    public static function set($key, $value)
    {
        try {
            if (!Schema::hasTable('settings')) {
                return false;
            }
            return self::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        } catch (\Exception $e) {
            Log::warning('Settings::set() error: ' . $e->getMessage());
            return false;
        }
    }
}
