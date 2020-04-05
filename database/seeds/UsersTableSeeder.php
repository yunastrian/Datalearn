<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'name' => 'Kurniandha Sukma',
        	'email' => 'kurnia@datalearn.com',
        	'password' => '$2y$10$20J2FUYhL22ovKKs2Kdfguo4oWEeBfkMxZipexw1BEtIxds3GnT9S',
        	'role' => 1
        ]);
        DB::table('users')->insert([
        	'name' => 'Fanny Akbar',
        	'email' => 'fanny@datalearn.com',
        	'password' => '$2y$10$20J2FUYhL22ovKKs2Kdfguo4oWEeBfkMxZipexw1BEtIxds3GnT9S',
        	'role' => 1
        ]);
        DB::table('users')->insert([
        	'name' => 'Irfan Sanemi',
        	'email' => 'irfan@datalearn.com',
        	'password' => '$2y$10$20J2FUYhL22ovKKs2Kdfguo4oWEeBfkMxZipexw1BEtIxds3GnT9S',
        	'role' => 0
        ]);
        DB::table('users')->insert([
        	'name' => 'Jojo Andika',
        	'email' => 'jojo@datalearn.com',
        	'password' => '$2y$10$20J2FUYhL22ovKKs2Kdfguo4oWEeBfkMxZipexw1BEtIxds3GnT9S',
        	'role' => 0
        ]);
    }
}
