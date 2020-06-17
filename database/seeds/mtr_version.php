<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class mtr_version extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_mtr_version')->insert([
            'allow_sdk_version_int' => 48,
            'api_key' => Str::random(20),
            'allow_int' => '0',
        ]);
    }
}
