<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => $this->faker->unique()->numberBetween(10,1000),
            'price' => $this->faker->numberBetween(10_000, 40_000),
        ];
    }
}
