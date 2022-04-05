<?php

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Entities\Sort;
use Medeiroz\LaravelDatatable\Enums\OrderEnum;
use Illuminate\Support\Str;
use Mockery\MockInterface;

it('constructor', function () {
    $sort = new Sort('first_name', OrderEnum::ASC);

    expect($sort->column)->toEqual('first_name');
    expect($sort->order)->toEqual(OrderEnum::ASC);
});

it('asc', function () {
    $sort = Sort::asc('last_name');

    expect($sort->column)->toEqual('last_name');
    expect($sort->order)->toEqual(OrderEnum::ASC);
});

it('desc', function () {
    $sort = Sort::desc('updated_at');

    expect($sort->column)->toEqual('updated_at');
    expect($sort->order)->toEqual(OrderEnum::DESC);
});

it('by with string', function (string $columnName, $direction, OrderEnum $expected) {
    $sort = Sort::by($columnName, $direction);

    expect($sort->column)->toEqual($columnName);
    expect($sort->order)->toEqual($expected);
})->with([
    ['my_string_asc', 'asc', OrderEnum::ASC],
    ['my_string_desc', 'desc', OrderEnum::DESC],
    ['my_string_asc_upper', 'ASC', OrderEnum::ASC],
    ['my_string_desc_upper', 'DESC', OrderEnum::DESC],

    ['my_enum_asc', OrderEnum::ASC, OrderEnum::ASC],
    ['my_enum_desc', OrderEnum::DESC, OrderEnum::DESC],

    ['my_stringable_asc', Str::of('asc'), OrderEnum::ASC],
    ['my_stringable_desc', Str::of('desc'), OrderEnum::DESC],
    ['my_stringable_ASC', Str::of('ASC'), OrderEnum::ASC],
    ['my_stringable_DESC', Str::of('DESC'), OrderEnum::DESC],
]);

it('apply', function () {
    $mockBuilder = Mockery::mock(Builder::class, function (MockInterface $mock) {
        $mock->shouldReceive('orderBy')
            ->once()
            ->with('first_name', 'asc')
            ->andReturnSelf();
    });

    $eagerLoad = Sort::asc('first_name');

    $eagerLoad->apply($mockBuilder);
});

