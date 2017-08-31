<?php

use Illuminate\Database\Seeder;

class CollegeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(range(1, 5) as $index)
        {
            DB::table('college')->insert([
                'name' => str_random(10),
            ]);
        }
    }
}
