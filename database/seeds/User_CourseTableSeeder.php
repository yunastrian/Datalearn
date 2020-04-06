<?php

use Illuminate\Database\Seeder;

class User_CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_course')->insert([
        	'id_user' => 5,
            'id_course' => 1,
            'role' => 1
        ]);
        DB::table('user_course')->insert([
        	'id_user' => 5,
            'id_course' => 2,
            'role' => 1
        ]);
        DB::table('user_course')->insert([
        	'id_user' => 6,
            'id_course' => 3,
            'role' => 1
        ]);
        DB::table('user_course')->insert([
        	'id_user' => 7,
            'id_course' => 1,
            'role' => 0
        ]);
        DB::table('user_course')->insert([
        	'id_user' => 8,
            'id_course' => 1,
            'role' => 0
        ]);
        DB::table('user_course')->insert([
        	'id_user' => 8,
            'id_course' => 2,
            'role' => 0
        ]);
    }
}
