<?php

namespace Database\Factories;
use App\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use \Bezhanov\Faker\Provider\Commerce;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $faker->locale('es_ES');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $name = $faker->productName();
        return [
            'stores_id'   => fake()->numberBetween(1, 1),
            'name'        => $name,
            'description' => $faker->text(),
            'price'    => 0.03 , //$faker->randomFloat(2, 10, 30),
            'quantity' => $faker->numberBetween(1, 100)
        ];
    }
}
