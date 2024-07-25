<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
{
    static $productNames = [
        'CCレモン',
        '三ツ矢サイダー',
        'キリンレモン',
        'コカコーラ',
        'お〜いお茶'
    ];

    return [
        'company_id' => $this->faker->randomNumber(1, 5),
        'product_name' => $this->faker->randomElement($productNames),
        'price' => $this->faker->numberBetween(100, 10000),
        'stock' => $this->faker->randomDigit,
        'comment' => $this->faker->sentence,
        'img_path' => 'https://picsum.photos/200/300',
    ];
}
}
