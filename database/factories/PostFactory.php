<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

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
            'user_id' => function() {
                return User::factory()->create()->id;
            },
            'description' => $this->faker->text(20),
            'photo' => $this->faker->regexify('[0-9]{10}') . '.jpg',
            'total_votes' => $this->faker->randomDigit
        ];
    }
}
