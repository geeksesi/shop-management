<?php

namespace App\Filters;


class ProductFilter extends AbstractFilter
{
    protected $filters = [
        'price' => PriceFilter::class,
        'category' => CategoryFilter::class,
    ];
}
