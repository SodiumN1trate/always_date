<?php

namespace Database\Factories;

use App\Models\LifeSchool;
use Illuminate\Database\Eloquent\Factories\Factory;

class LifeSchoolFactory extends Factory {
    private static $number = 1;
    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition() {
        if(self::$number === 101){
            self::$number = 1;
        }
        return  [
            'title' => $this->faker->text(30),
            'description' => $this->faker->text(2000),
            'reading_time' => $this->faker->numberBetween(4, 20),
            'number' => self::$number++,
        ];
    }
}
