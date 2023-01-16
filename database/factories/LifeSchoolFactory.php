<?php

namespace Database\Factories;

use App\Models\LifeSchool;
use Illuminate\Database\Eloquent\Factories\Factory;

class LifeSchoolFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return  [
            'title' => $this->faker->text(30),
            'gender' => $this->faker->numberBetween(0, 1),
            'description' => $this->faker->text(2000),
            'reading_time' => $this->faker->numberBetween(4, 20),
            'number' => $gender = $this->faker->unique()->numberBetween(0, 100),
        ];
    }
}
