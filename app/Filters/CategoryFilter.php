<?php
namespace App\Filters;

use App\Models\Category;

class CategoryFilter implements Filter
{
    public function filter($builder, $value, $request)
    {
        $categoryIds = array_filter(array_map(function ($id){
            $id = trim($id);
            if (ctype_digit($id))
                return trim($id);
            return null;
        } ,explode(",",$value)));
        return $builder->whereBelongsTo(Category::find($categoryIds));
    }
}
