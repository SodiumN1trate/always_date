<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        $gender = $this->faker->numberBetween($min = 0, $max = 1);
        $age = rand(18, 100);
        return [
            'avatar' => 'https://xsgames.co/randomusers/avatar.php?g=' . ($gender === 1 ? 'female' : 'male') . '&y=' . $age,
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $gender,
            'provider_id' => $this->faker->unique()->numberBetween($min = 100000, $max = 1000000),
            'age' => $age,
            'rate_count' => rand(0, 100),
            'about_me' => $this->faker->text(350),
            'rating' => $this->faker->randomFloat(2,0, 10),
            'read_school_exp' => rand(0, 100),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified() {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
