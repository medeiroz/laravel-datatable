<?php

namespace Medeiroz\LaravelDatatable\Conditions;

use Medeiroz\LaravelDatatable\Conditions\Contracts\ConditionInterface;
use Medeiroz\LaravelDatatable\Entities\Filter;

class ConditionMaker
{
    public static function make(Filter $filter): ConditionInterface
    {
        $class = $filter->getCondition()->getClass();

        return app()->makeWith($class, ['filter' => $filter]);
    }
}
