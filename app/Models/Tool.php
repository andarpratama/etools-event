<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'min_order',
        'image_url',
        'badge_color',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(ToolImage::class)->orderBy('sort_order');
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->first()?->image_url ?? $this->image_url ?? null;
    }
}
