<?php

use Illuminate\Database\Seeder;

class SpreadsheetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('spreadsheets')->insert([
            'id' => 1,
            'cell' => 'A1',
            'value' => '23',
            'type' => 0
        ]);
    }
}
