<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Post;
use App\Models\User;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => function() {
                return Post::factory()->create()->id;
            },
            'user_id' => function() {
                return User::factory()->create()->id;
            },
            'text' => $this->faker->text(100)
        ];
    }
}
