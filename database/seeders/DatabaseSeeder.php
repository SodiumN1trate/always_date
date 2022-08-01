<?php

namespace Database\Seeders;

use App\Models\LifeSchool;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RatingLog;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        User::factory()->times(1000)->create();
        User::create([
            "name"=>"Test user",
            "email"=>"tester@rvt.lv",
            "provider_id"=>1231232414112312
        ]);

        RatingLog::create([
            "user_id"=> 1,
            "rater_id"=> 2,
            "rating"=> 5
        ]);

        LifeSchool::factory()->times(100)->create();
    }
}
