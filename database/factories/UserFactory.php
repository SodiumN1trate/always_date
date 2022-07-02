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
        $userAvatars = [
            "https://this-person-does-not-exist.com/img/avatar-64006504bd22fc3d75ec5794dde62343.jpg",
            "https://this-person-does-not-exist.com/img/avatar-a0c3eed44c9a02a2e3d744a2f592b820.jpg",
            "https://this-person-does-not-exist.com/img/avatar-5c358f4704dc9ed665fa2f4d87fa0a85.jpg",
            "https://this-person-does-not-exist.com/img/avatar-25525f06b2c50e7dcaabee128a44fd60.jpg",
            "https://this-person-does-not-exist.com/img/avatar-51073a0eb5d4d4af8590089166f16998.jpg",
            "https://this-person-does-not-exist.com/img/avatar-1ca35f3fdd3b73186c7a55d5802ab0f0.jpg",
            "https://this-person-does-not-exist.com/img/avatar-2a390a0ceafd780d4403a9456c01b968.jpg",
            "https://this-person-does-not-exist.com/img/avatar-6d52b21c25192d7bd67ad04f36cbf804.jpg",
            "https://this-person-does-not-exist.com/img/avatar-a12564cef62e3ccc20f89b5af30c5946.jpg",
            "https://this-person-does-not-exist.com/img/avatar-43b04353b58663377d3f368a1daf9ef6.jpg",
            "https://this-person-does-not-exist.com/img/avatar-1f86b21124e5d93e8cc1606f1cf2d385.jpg",
        ];
        shuffle($userAvatars);
        return [
            'avatar' => $userAvatars[0],
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $this->faker->numberBetween($min = 0, $max = 1),
            'provider_id' => $this->faker->unique()->numberBetween($min = 100000, $max = 1000000),
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
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
