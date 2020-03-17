<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $password = Hash::make('upass');

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => $password,
        ]);


    }
}
