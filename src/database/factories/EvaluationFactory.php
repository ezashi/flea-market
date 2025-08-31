<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'evaluator_id' => User::factory(),
            'evaluated_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}