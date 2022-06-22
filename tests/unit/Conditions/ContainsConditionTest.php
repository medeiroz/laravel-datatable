<?php

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;
use Medeiroz\LaravelDatatable\Enums\GroupConditionEnum;
use Mockery\MockInterface;

it('Apply', function () {
    $mockBuilder = Mockery::mock(Builder::class, function (MockInterface $mock) {
        $mock->shouldReceive('whereRaw')
            ->with('unaccent(name) ILIKE unaccent(?)', ['%medeiroz%'], GroupConditionEnum::AND->value)
            ->once()
            ->andReturnSelf();
    });


    $filter = new Filter(
        'name',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'medeiroz',
        GroupConditionEnum::AND
    );

    $condition = new ContainsCondition($filter);
    $resultBuilder = $condition->apply($mockBuilder);

    expect($resultBuilder)->toEqual($mockBuilder);
});
