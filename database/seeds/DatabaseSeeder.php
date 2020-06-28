<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(TopicsTableSeeder::class);
        $this->call(SpreadsheetsTableSeeder::class);
        $this->call(User_CourseTableSeeder::Class);
    }
}
