<?php

namespace Database\Factories;

use App\Models\Desk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeskList>
 */
class DeskListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    //protected $model = DeskList::class;
    public function definition()
    {
        return [
            //	id	name	desk_id	created_at	updated_at
            'name' => $this->faker->word,
            'desk_id' => Desk::get()->random()->id,
        ];
    }
}