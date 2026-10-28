<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // [name, description, price, discount_percent, is_featured]
        $menu = [
            'Pizza' => [
                ['Delicious Pizza', 'Classic cheese pizza with our signature tomato sauce.', 5.50, 0, true],
                ['Fruiti Beef Pizza', 'Juicy beef, pineapple and mozzarella.', 8.90, 20, true],
                ['Pepperoni Pizza', 'Loaded with spicy pepperoni and extra cheese.', 9.50, 0, false],
                ['Hawaiian Pizza', 'Ham, pineapple and cheese on a golden crust.', 9.00, 40, true],
            ],
            'Pasta' => [
                ['Spaghetti Shrimp', 'Spaghetti with garlic butter shrimp.', 6.50, 0, true],
                ['Chicken Alfredo', 'Creamy alfredo sauce with grilled chicken.', 7.20, 0, false],
            ],
            'Chicken' => [
                ['Crispy Chicken Wings', 'Six crispy wings with dipping sauce.', 5.90, 10, false],
            ],
            'Salad' => [
                ['Chicken Salad', 'Fresh greens, grilled chicken and vinaigrette.', 4.50, 0, false],
            ],
            'Sides' => [
                ['Garlic Bread', 'Toasted garlic bread with herb butter.', 2.50, 0, false],
            ],
            'Desserts' => [
                ['Chocolate Lava Cake', 'Warm chocolate cake with a molten center.', 3.50, 0, false],
            ],
            'Drinks' => [
                ['Coca-Cola 500ml', 'Chilled soft drink.', 1.00, 0, false],
            ],
        ];

        foreach ($menu as $categoryName => $items) {
            $category = Category::where('slug', Str::slug($categoryName))->first();

            foreach ($items as [$name, $description, $price, $discount, $featured]) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'discount_percent' => $discount,
                        'is_featured' => $featured,
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}