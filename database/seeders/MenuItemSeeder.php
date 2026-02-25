<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\MenuCategory;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $categories = MenuCategory::pluck('id')->toArray();

        $items = [
            'Tea', 'Coffee', 'Pilau', 'Ugali Beef',
            'Chapati Beans', 'Chicken Fry',
            'Soda', 'Fresh Juice'
        ];

        foreach ($items as $item) {
            MenuItem::create([
                'name' => $item,
                'menu_category_id' => $categories[array_rand($categories)],
                'price' => rand(200, 1200)
            ]);
        }
    }
}
