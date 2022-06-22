<?php

namespace Medeiroz\LaravelDatatable\Entities;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Medeiroz\LaravelDatatable\Enums\GroupConditionEnum;

class FilterGroup
{

    public function __construct(
        public readonly Collection $filters,
        public readonly string|GroupConditionEnum $groupCondition = GroupConditionEnum::AND,
    )
    {

    }

    public function apply(Builder $builder): Builder
    {
        $closure = function (Builder $whereBuilder) {
            $this->filters
                ->each(fn (Filter $filter) => $filter->apply($whereBuilder));
        };

        if ($this->groupCondition === GroupConditionEnum::AND) {
            $builder->where($closure);
        } elseif ($this->groupCondition === GroupConditionEnum::OR) {
            $builder->orWhere($closure);
        }

        return $builder;
    }

}
