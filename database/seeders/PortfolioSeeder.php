<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $portfolios = [
            [
                'title' => 'Sound System Event',
                'category' => 'Sound System',
                'image_url' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=800',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Lighting Event',
                'category' => 'Lighting',
                'image_url' => 'https://images.unsplash.com/photo-1518972559570-7cc1309f3229?w=800',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Pernikahan Outdoor',
                'category' => 'Wedding',
                'image_url' => 'https://images.unsplash.com/photo-1529634806980-85c3dd6d34ac?w=800',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Panggung Event',
                'category' => 'Panggung',
                'image_url' => 'https://images.unsplash.com/photo-1506157786151-b8491531f063?w=800',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Seminar & Gathering',
                'category' => 'Corporate',
                'image_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Festival & Acara Lapangan',
                'category' => 'Outdoor Event',
                'image_url' => 'https://images.unsplash.com/photo-1503428593586-e225b39bddfe?w=800',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create($portfolio);
        }
    }
}
