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
        LifeSchool::factory()->times(100)->create();
    }
}
