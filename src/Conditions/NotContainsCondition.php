<?php

namespace Medeiroz\LaravelDatatable\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\Contracts\ConditionInterface;
use Medeiroz\LaravelDatatable\Entities\Filter;

class NotContainsCondition implements ConditionInterface
{
    public function __construct(
        protected readonly Filter $filter,
    ) {
    }

    public function apply(Builder $builder): Builder
    {
        $binds = [$this->filter->getColumn(), "%{$this->filter->getValue()}%"];

        return $builder
            ->whereRaw('unaccent(?) NOT ILIKE unaccent(?)', $binds);
    }
}
