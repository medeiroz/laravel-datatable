<?php

namespace Medeiroz\LaravelDatatable\Conditions;

use Medeiroz\LaravelDatatable\Conditions\Contracts\ConditionInterface;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Illuminate\Database\Eloquent\Builder;

class ContainsCondition implements ConditionInterface
{
    public function __construct(
        protected readonly Filter $filter,
    )
    {
    }


    public function apply(Builder $builder): Builder
    {
        $binds = [$this->filter->getColumn(), "%{$this->filter->getValue()}%"];

        return $builder
            ->whereRaw('unaccent(?) ILIKE unaccent(?)', $binds);
    }
}
