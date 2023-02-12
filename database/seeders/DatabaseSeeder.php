<?php

namespace Database\Seeders;

use App\Models\ChatRoom;
use App\Models\LifeSchool;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RatingLog;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'edit.life_school']);
        Permission::create(['name' => 'delete.life_school']);
        Permission::create(['name' => 'view.life_school']);
        Permission::create(['name' => 'create.life_school']);

        $role = Role::create(['name' => 'Administrators'])
            ->givePermissionTo([
                    'edit.life_school',
                    'delete.life_school',
                    'view.life_school',
                    'create.life_school',
                ]);
        Role::create(['name' => 'Lietotājs'])
            ->givePermissionTo([
                    'view.life_school',
                ]);
        User::factory()
            ->times(1000)
            ->create()
            ->map(function ($user) {
                $user->assignRole('Lietotājs');
            });

        User::create([
            'firstname' =>'Test',
            'lastname' => 'user',
            'email' => 'tester@rvt.lv',
            'provider_id' => 1231232414112312,
        ]);

        RatingLog::create([
            'user_id'=> 1,
            'rater_id' => 2,
            'rating' => 5,
        ]);

        ChatRoom::create([
            'user1_id' => 1,
            'user2_id' => 2,
        ]);
        ChatRoom::create([
            'user1_id' => 3,
            'user2_id' => 4,
        ]);

        LifeSchool::factory()->times(100)->create();
    }
}
