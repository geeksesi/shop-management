<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        DB ::table('permissions')->insert([
            'name'=>'Create Products',
        ]);
        DB ::table('permissions')->insert([
            'name'=>'Edit Products',
        ]);
        DB ::table('permissions')->insert([
            'name'=>'Delete Products',
        ]);
        DB ::table('permissions')->insert([
            'name'=>'Create Categories',
        ]);
        DB ::table('permissions')->insert([
            'name'=>'Edit Categories',
        ]);
        DB ::table('permissions')->insert([
            'name'=>'Delete Categories',
        ]);
    }
}
