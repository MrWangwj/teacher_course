<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \Illuminate\Support\Facades\DB::table('settings')->insert([
            [
                'key' => 'nowTermId',
                'value' => 1
            ]
        ]);
    }
}
