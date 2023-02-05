<?php
namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    protected $request;

    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    protected function getFilters()
    {

    }
    public function filter(Builder $builder)
    {

    }
    protected function resolveFilter($filter)
    {

    }
}
