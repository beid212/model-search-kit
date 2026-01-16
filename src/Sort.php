<?php

namespace Beid212\ModelSearchKit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Sort
{
    protected Builder $builder;

    abstract public function sortKey():string;

    public function apply(Builder $builder, array $sortData)
    {
        $this->builder = $builder;
        if(!isset($sortData[$this->sortKey()]))
            return $builder;
        
        $method = $sortData[$this->sortKey()];
        if(!is_string($method))
            return $builder;
        
        $method = Str::camel($method);

        if(!method_exists($this, $method))
            return $builder;

        $this->{$method}();

        return $builder;
    }
}
