<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'brand' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100, 50000),
            'condition' => $this->faker->randomElement(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い']),
            'image' => 'storage/images/items/' . $this->faker->uuid() . '.jpg',
            'seller_id' => User::factory(),
            'buyer_id' => null,
            'sold' => false,
            'is_transaction_completed' => false,
            'payment_method' => null,
        ];
    }

    public function sold()
    {
        return $this->state(function (array $attributes) {
            return [
                'sold' => true,
                'buyer_id' => User::factory(),
                'payment_method' => $this->faker->randomElement(['コンビニ払い', 'カード払い']),
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'sold' => true,
                'is_transaction_completed' => true,
                'buyer_id' => User::factory(),
                'payment_method' => $this->faker->randomElement(['コンビニ払い', 'カード払い']),
            ];
        });
    }
}