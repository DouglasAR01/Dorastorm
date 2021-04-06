<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(15),
            'content' => $this->faker->paragraphs(10, true),
            'description' => $this->faker->sentence(20),
            'visible' => 1,
            'private' => random_int(0,1),
        ];
    }
}
