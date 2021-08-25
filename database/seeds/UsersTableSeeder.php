<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker)
    {
//    DB::unprepared(file_get_contents(__DIR__ . './../dumps/users.sql'));

        DB::table('users')->insert([
            [
                'user_login' => 'admin',
                'username' => mb_strtolower($faker->firstName),
                'user_pass' => Hash::make('password'), //$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
                'creation_date' => now()->format('Y-m-d H:i:s'),
                'created_by_user_id' => 1,
                'remember_token' => Str::random(10),
                'detections_check_access' => rand(0, 1),
                'uiks_view_access' => rand(0, 1),
                'events_view_access' => rand(0, 1),
                'events_form_access' => rand(0, 1),
                'stats_view_access' => rand(0, 1),
                'role_id' => 1,
            ],
            [
                'user_login' => mb_strtolower($faker->userName),
                'username' => mb_strtolower($faker->firstName),
                'user_pass' => Hash::make('password'), //$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
                'creation_date' => now()->format('Y-m-d H:i:s'),
                'created_by_user_id' => 1,
                'remember_token' => Str::random(10),
                'detections_check_access' => rand(0, 1),
                'uiks_view_access' => rand(0, 1),
                'events_view_access' => rand(0, 1),
                'events_form_access' => rand(0, 1),
                'stats_view_access' => rand(0, 1),
                'role_id' => 2,
            ],
            [
                'user_login' => mb_strtolower($faker->userName),
                'username' => mb_strtolower($faker->firstName),
                'user_pass' => Hash::make('password'), //$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
                'creation_date' => now()->format('Y-m-d H:i:s'),
                'created_by_user_id' => 1,
                'remember_token' => Str::random(10),
                'detections_check_access' => rand(0, 1),
                'uiks_view_access' => rand(0, 1),
                'events_view_access' => rand(0, 1),
                'events_form_access' => rand(0, 1),
                'stats_view_access' => rand(0, 1),
                'role_id' => 3,
            ],
        ]);

        $user = new \App\Models\User;
        $user->username = mb_strtolower($faker->firstName);
        $user->user_login = mb_strtolower($faker->userName);
        $user->user_pass = Hash::make('password'); //$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi;
        $user->creation_date = now()->format('Y-m-d H:i:s');
        $user->created_by_user_id = 1;
        $user->remember_token = Str::random(10);
        $user->detections_check_access = rand(0, 1);
        $user->uiks_view_access = rand(0, 1);
        $user->events_view_access = rand(0, 1);
        $user->events_form_access = rand(0, 1);
        $user->stats_view_access = rand(0, 1);
        $user->role_id = 3;
        $user->save();

        $user->checks()->attach([1, 4]);
    }
}
