<?php

namespace Beid212\ModelSearchKit;

use Illuminate\Database\Eloquent\Builder;
use ReflectionMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Beid212\ModelSearchKit\Exceptions\FilterException;

abstract class Filter
{
    protected $params = [];
    protected Builder $builder;

    private $possibleParamTypes = [
        'string',
        'float',
        'bool',
        'int',
        Carbon::class
    ];

    protected function possibleParamTypes():array
    {
        return [];
    }

    public function getFilterParams()
    {
        return $this->params;
    }

    public function apply(Builder $builder, array $paramValues):Builder
    {
        $this->builder = $builder;
        $possibleParamTypes = array_merge($this->possibleParamTypes,$this->possibleParamTypes());

        foreach($paramValues as $method => $value)
        {
            try{
                $method = Str::camel($method);

                if(!method_exists($this, $method))
                    continue;
                $methodData = new ReflectionMethod($this, $method);

                $parameter = $methodData->getParameters()[0];

                if(!in_array($parameter->getType()->getName(), $possibleParamTypes))
                    continue;

                $value = $this->bringToDesiredType($parameter->getType()->getName(), $value);

                if(is_null($value))
                    throw new \Exception('Type conversion error');

                $this->{$method}($value);
            }
            catch(\Exception $ex){

                if(is_bool(config('mskit.log'))&&config('mskit.log')){
                    Log::warning('Invalid date format in filter', [
                        'date' => now(),
                        'error' => $ex->getMessage()
                    ]);
                }

                //throw new FilterException('Filter application error! Error parameter: '. $method);
            }
        }

        return $this->builder;
    }

    protected function bringToDesiredType(string $type, $value)
    {
        switch($type)
        {
            case 'string':{
                return (string) $value;
                break;
            }
            case 'float':{
                return (float) $value;
                break;
            }
            case 'bool':{
                return (bool) $value;
                break;
            }
            case 'int':{
                return (bool) $value;
                break;
            }
            case Carbon::class:{
                return Carbon::parse($value);
                break;
            }
        }
        return null;
    }
}
