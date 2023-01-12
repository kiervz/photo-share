<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'description' => $this->faker->text(20),
            'photo' => $this->faker->regexify('[0-9]{10}') . '.jpg',
            'total_votes' => $this->faker->randomDigit
        ];
    }
}
