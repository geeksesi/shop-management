<?php

namespace App\Filters;

interface Filter
{
    public function filter($builder, $value, $request);
}
