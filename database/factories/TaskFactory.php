<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     * 
     * @return array<string, mixed>
     */
    //protected $model = Task::class;
    public function definition()
    {
        return [
            //	id	name	card_id	created_at	updated_at	
            'name' => $this->faker->word,
            'card_id' => Card::get()->random()->id,
        ];
    }
}
