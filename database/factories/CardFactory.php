<?php

namespace Database\Factories;

use App\Models\DeskList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    //protected $model = Card::class;
    public function definition()
    {
        return [
            //id	name	desk_list_id	created_at	updated_at	
            'name' => $this->faker->word,
            'desk_list_id' => DeskList::get()->random()->id,
        ];
    }
}

