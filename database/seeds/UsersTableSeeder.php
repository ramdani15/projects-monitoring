<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
        	[
        		'name' => 'super',
        		'username' => 'super',
        		'email' => 'super@gmail.com',
        		'role' => 'super',
        		'password' => Hash::make(123),
        	],
        	[
        		'name' => 'pm1',
        		'username' => 'pm1',
        		'email' => 'pm1@gmail.com',
        		'role' => 'pm',
        		'password' => Hash::make(123),
        	],
        	[
        		'name' => 'pm2',
        		'username' => 'pm2',
        		'email' => 'pm2@gmail.com',
        		'role' => 'pm',
        		'password' => Hash::make(123),
        	],
        	[
        		'name' => 'programmer1',
        		'username' => 'programmer1',
        		'email' => 'programmer1@gmail.com',
        		'role' => 'programmer',
        		'password' => Hash::make(123),
        	],
        	[
        		'name' => 'programmer2',
        		'username' => 'programmer2',
        		'email' => 'programmer2@gmail.com',
        		'role' => 'programmer',
        		'password' => Hash::make(123),
        	],
        ]);
    }
}
