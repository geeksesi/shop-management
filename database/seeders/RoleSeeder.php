<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        DB ::table('roles')->insert([
            'name'=>'SUPER_ADMIN',
        ]);
        DB ::table('roles')->insert([
            'name'=>'ADMIN',
        ]);
        DB ::table('roles')->insert([
            'name'=>'SALES_MANAGER',
        ]);
        DB ::table('roles')->insert([
            'name'=>'SALES_STAFF',
        ]);
    }
}
