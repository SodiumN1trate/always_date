<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'avatar' => "https://thispersondoesnotexist.com/",
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => rand(0,1),
            'provider_id' => $this->faker->unique()->numberBetween($min = 100000, $max = 1000000),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
