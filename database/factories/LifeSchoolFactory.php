<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LifeSchoolFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return  [
            'title' => $this->faker->text(50),
            'gender' => $this->faker->numberBetween(0, 1),
            'description' => $this->faker->text(2000)
        ];
    }
}
