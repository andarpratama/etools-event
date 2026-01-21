<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolImage;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $tools = [
            [
                'name' => 'AC 5pk Standing',
                'category' => 'Pendingin',
                'description' => 'AC standing 5pk untuk event indoor',
                'price' => 750000,
                'min_order' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1621905251918-48416bd8575a?w=600',
                'badge_color' => 'primary',
                'is_active' => true,
            ],
            [
                'name' => 'Misty Fan',
                'category' => 'Pendingin',
                'description' => 'Misty fan untuk pendingin udara outdoor',
                'price' => 350000,
                'min_order' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',
                'badge_color' => 'info',
                'is_active' => true,
            ],
            [
                'name' => 'Genset 40 kVA',
                'category' => 'Kelistrikan',
                'description' => 'Generator set 40 kVA untuk kebutuhan listrik event',
                'price' => 2200000,
                'min_order' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
                'badge_color' => 'warning',
                'is_active' => true,
            ],
            [
                'name' => 'Genset 60 kVA',
                'category' => 'Kelistrikan',
                'description' => 'Generator set 60 kVA untuk kebutuhan listrik event',
                'price' => 2700000,
                'min_order' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
                'badge_color' => 'warning',
                'is_active' => true,
            ],
            [
                'name' => 'Genset 80 kVA',
                'category' => 'Kelistrikan',
                'description' => 'Generator set 80 kVA untuk kebutuhan listrik event',
                'price' => 3200000,
                'min_order' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
                'badge_color' => 'warning',
                'is_active' => true,
            ],
            [
                'name' => 'Genset 100 kVA',
                'category' => 'Kelistrikan',
                'description' => 'Generator set 100 kVA untuk kebutuhan listrik event',
                'price' => 3800000,
                'min_order' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
                'badge_color' => 'warning',
                'is_active' => true,
            ],
            [
                'name' => 'Genset 150 kVA',
                'category' => 'Kelistrikan',
                'description' => 'Generator set 150 kVA untuk kebutuhan listrik event',
                'price' => 4800000,
                'min_order' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
                'badge_color' => 'warning',
                'is_active' => true,
            ],
            [
                'name' => 'Genset 200 kVA',
                'category' => 'Kelistrikan',
                'description' => 'Generator set 200 kVA untuk kebutuhan listrik event',
                'price' => 6000000,
                'min_order' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
                'badge_color' => 'warning',
                'is_active' => true,
            ],
            [
                'name' => 'Genset 250 kVA',
                'category' => 'Kelistrikan',
                'description' => 'Generator set 250 kVA untuk kebutuhan listrik event',
                'price' => 9000000,
                'min_order' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
                'badge_color' => 'warning',
                'is_active' => true,
            ],
        ];

        foreach ($tools as $toolData) {
            $imageUrl = $toolData['image_url'] ?? null;
            unset($toolData['image_url']); // Remove image_url from tool data
            
            // Set image_url to null or empty string if field is required
            $toolData['image_url'] = $imageUrl ?? '';
            
            $tool = Tool::create($toolData);
            
            // Create ToolImage entry for the image_url
            if ($imageUrl) {
                ToolImage::create([
                    'tool_id' => $tool->id,
                    'image_url' => $imageUrl,
                    'type' => 'image',
                    'sort_order' => 0,
                ]);
            }
        }
    }
}
