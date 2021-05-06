<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET foreign_key_checks = 0');
        DB::table('tenants')->truncate();
        DB::statement('SET foreign_key_checks = 1');

        $data = [[
            'id' => 1,
            'name' => 'Solatek',
            'email' => 'solatek@gmail.com',
            'phone' => '0123456789',
            'address' => 'hà nội',
            'created_at' => Carbon::now(),
        ], [
            'id' => 2,
            'name' => 'Solashi',
            'email' => 'solashi@gmail.com',
            'phone' => '0123456789',
            'address' => 'hà nội',
            'created_at' => Carbon::now(),
        ], [
            'id' => 3,
            'name' => 'Amela',
            'email' => 'amela@gmail.com',
            'phone' => '0123456789',
            'address' => 'hà nội',
            'created_at' => Carbon::now(),
        ], [
            'id' => 4,
            'name' => 'Arrow Tech',
            'email' => 'arrow@gmail.com',
            'phone' => '0123456789',
            'address' => 'hà nội',
            'created_at' => Carbon::now(),
        ]];
        DB::table('tenants')->insert($data);
    }
}
