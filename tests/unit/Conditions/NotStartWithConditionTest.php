<?php

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\NotStartWithCondition;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;
use Mockery\MockInterface;

it('Apply', function () {
    $mockBuilder = Mockery::mock(Builder::class, function (MockInterface $mock) {
        $mock->shouldReceive('whereRaw')
            ->with('unaccent(?) NOT ILIKE unaccent(?)', ['name', 'medeiroz%'])
            ->once()
            ->andReturnSelf();
    });


    $filter = new Filter(
        'name',
        ColumnTypeEnum::STRING,
        ConditionEnum::NOT_START_WITH,
        'medeiroz',
    );

    $condition = new NotStartWithCondition($filter);
    $resultBuilder = $condition->apply($mockBuilder);

    expect($resultBuilder)->toEqual($mockBuilder);
});
