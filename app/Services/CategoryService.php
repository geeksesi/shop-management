<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{

    public function getTree()
    {
        return Category::whereNull("parent_id")->with("children")->latest()->get();
    }
}
