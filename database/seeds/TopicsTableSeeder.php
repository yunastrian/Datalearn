<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('topics')->insert([
            'id_course' => 1,
            'name' => 'Baris dan Kolom',
            'content' => 'Baris adalah blabalbalbalbalbala. Kolom adalah blabalbalblablala.',
            'id_spreadsheet' => 'null'
        ]);
        DB::table('topics')->insert([
            'id_course' => 1,
            'name' => 'Formula',
            'content' => 'Formula adalah blabalbalblablala.',
            'id_spreadsheet' => 'null'
        ]);
        DB::table('topics')->insert([
            'id_course' => 2,
            'name' => 'Average',
            'content' => 'Average adalah salah satu....',
            'id_spreadsheet' => 'null'
        ]);
    }
}
