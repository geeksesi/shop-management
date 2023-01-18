<?php

namespace Database\Seeders;

use App\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories=['News','Opinion','Tutorial','Review'];
        foreach ($categories as $category)
        {
            Category::create(['name'=>$category]);
        }
    }
}
