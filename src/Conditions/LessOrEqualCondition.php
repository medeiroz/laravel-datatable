<?php

namespace Medeiroz\LaravelDatatable\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\Contracts\ConditionInterface;
use Medeiroz\LaravelDatatable\Entities\Filter;

class LessOrEqualCondition implements ConditionInterface
{
    public function __construct(
        protected readonly Filter $filter,
    ) {
    }

    public function apply(Builder $builder): Builder
    {
        return $builder
            ->where($this->filter->getColumn(), '<=', $this->filter->getValue());
    }
}
