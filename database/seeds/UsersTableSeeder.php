<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET foreign_key_checks = 0');
        DB::table('users')->truncate();
        DB::statement('SET foreign_key_checks = 1');

        $data = [[
            'id' => 1,
            'name' => 'Đỗ Văn Huy',
            'email' => 'huydv@gmail.com',
            'password' => Hash::make('123456'),
            'position' => 2,
            'tenant_id' => 1,
            'phone' => '0123456789',
            'gender' => 'Nam',
            'address' => 'ninh bình',
            'created_at' => Carbon::now(),
        ], [
            'id' => 2,
            'name' => 'Nguyễn Văn A',
            'email' => 'abcd@gmail.com',
            'password' => Hash::make('123456'),
            'position' => 2,
            'tenant_id' => 2,
            'phone' => '0123456789',
            'gender' => 'Nữ',
            'address' => 'bắc ninh',
            'created_at' => Carbon::now(),
        ]];
        DB::table('users')->insert($data);
    }
}
