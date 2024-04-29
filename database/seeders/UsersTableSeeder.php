<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'), // Remplacez 'your_predefined_password' par le mot de passe que vous souhaitez utiliser
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 1
        ]);
    }
}
