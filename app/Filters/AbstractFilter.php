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
        return array_filter($this->request->only(array_keys($this->filters)));
    }
    public function filter(Builder $builder)
    {
        foreach($this->getFilters() as $filter => $value)
        {
            $this->resolveFilter($filter)->filter($builder, $value , $this->request);
        }
        return $builder;
    }
    protected function resolveFilter($filter)
    {
        return new $this->filters[$filter];
    }
}
