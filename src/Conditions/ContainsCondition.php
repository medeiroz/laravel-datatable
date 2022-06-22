<?php

namespace Medeiroz\LaravelDatatable\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\Contracts\ConditionInterface;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;

class ContainsCondition implements ConditionInterface
{
    public function __construct(
        protected readonly Filter $filter,
    ) {
    }

    public function apply(Builder $builder): Builder
    {
        if ($this->filter->getType() === ColumnTypeEnum::DATE
            || $this->filter->getType() === ColumnTypeEnum::DATE_TIME) {
            return $this->applyForDate($builder);
        }

        return $builder
            ->whereRaw(
                "unaccent({$this->filter->getColumn()}) ILIKE unaccent(?)",
                ["%{$this->filter->getValue()}%"],
                $this->filter->getGroupCondition()->value
            );
    }

    private function applyForDate(Builder $builder): Builder
    {
        return $builder
            ->whereRaw(
                "CAST({$this->filter->getColumn()} AS VARCHAR) ILIKE ?",
                ["%{$this->filter->getValue()}%"],
                $this->filter->getGroupCondition()->value
            );
    }
}
