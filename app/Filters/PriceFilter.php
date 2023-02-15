<?php
namespace App\Filters;

class PriceFilter implements Filter
{
    public function filter($builder, $value, $request)
    {
        $op = "=";
        if (!is_null($request->price_action))
        {
            if ($request->price_action == "less_than")
                $op = "<";
            else if ($request->price_action == "more_than")
                $op = ">";
        }
        return $builder->where('price' , $op , $value);
    }
}
