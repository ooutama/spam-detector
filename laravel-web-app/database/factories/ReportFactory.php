<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

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
