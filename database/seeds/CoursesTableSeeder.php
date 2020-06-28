<?php

use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
        	'name' => 'Pengenalan Spreadsheet',
        	'description' => 'Belajar pengenalan terkait spreadsheet dari hal yang paling dasar. Cocok untuk anda yang ingin mendalami spreadsheet.'
        ]);
        DB::table('courses')->insert([
        	'name' => 'Spreadsheet Expert',
        	'description' => 'Belajar spreadsheet untuk level menengah. Cocok untuk anda yang ingin mendalami spreadsheet.'
        ]);
        DB::table('courses')->insert([
        	'name' => 'Data Analisis',
        	'description' => 'Belajar data analisis menggunakan spreadsheet. Cocok untuk anda pecinta data.'
        ]);
    }
}
