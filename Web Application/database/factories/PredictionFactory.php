<?php

namespace Database\Factories;

use App\Models\Prediction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PredictionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Prediction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message_id'    => Message::factory()->create()->id,   
            'is_spam'    => rand(0,1),   
        ];
    }
}
