<?php

namespace Medeiroz\LaravelDatatable\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Medeiroz\LaravelDatatable\Enums\OrderEnum;

class Sort
{
    final public function __construct(
        public readonly string $column,
        public readonly OrderEnum $order = OrderEnum::ASC,
    ) {
    }

    public static function asc(string $column): Sort
    {
        return new Sort($column, OrderEnum::ASC);
    }

    public static function desc(string $column): Sort
    {
        return new Sort($column, OrderEnum::DESC);
    }

    public static function by(string $column, string|Stringable|OrderEnum $order): Sort
    {
        $orderEnum = ($order instanceof OrderEnum)
            ? $order
            : OrderEnum::from(Str::of($order)->lower());

        return new Sort($column, $orderEnum);
    }

    public function apply(Builder $builder): self
    {
        $builder->orderBy($this->column, $this->order->value);

        return $this;
    }
}
