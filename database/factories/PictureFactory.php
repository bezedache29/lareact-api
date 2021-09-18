<?php

namespace Database\Factories;

use App\Models\Picture;
use Illuminate\Database\Eloquent\Factories\Factory;

class PictureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Picture::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(rand(5, 10), true),
            'description' => $this->faker->sentences(rand(20, 45), true),
            'image' => 'pictures/1631831499-56.jpg'
        ];
    }
}
