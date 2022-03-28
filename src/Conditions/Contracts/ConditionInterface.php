<?php

namespace Medeiroz\LaravelDatatable\Conditions\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface ConditionInterface
{
    public function apply(Builder $builder): Builder;
}
