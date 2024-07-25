<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    public function run() 
    {
        Product::factory()->count(15)->create();
    }
}

