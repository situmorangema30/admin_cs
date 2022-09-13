<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'username' => 'Admin',
            'password' => bcrypt('admin')
        ]);

        ProductCategory::create([
            'category' => 'Elektronik',
            'description' => '-',
        ]);

        Product::create([
            'name' => 'Laptop',
            'code' => 'L01',
            'product_categories_id' => 1,
            'price' => 2000000.00,
            'purchase_price' => 2200000.00,
            'short_description' => '-',
            'description' => '-',
            'status' => 1,
            'new_product' => 1,
            'best_seller' => 1,
            'featured' => 1,
        ]);

        Product::create([
            'name' => 'Komputer',
            'code' => 'K01',
            'product_categories_id' => 1,
            'price' => 5000000.00,
            'purchase_price' => 7000000.00,
            'short_description' => '-',
            'description' => '-',
            'status' => 1,
            'new_product' => 1,
            'best_seller' => 1,
            'featured' => 1,
        ]);

        Product::create([
            'name' => 'Komputer 2',
            'code' => 'K02',
            'product_categories_id' => 1,
            'price' => 5000000.00,
            'purchase_price' => 7000000.00,
            'short_description' => '-',
            'description' => '-',
            'status' => 0,
            'new_product' => 1,
            'best_seller' => 1,
            'featured' => 1,
        ]);
    }
}
