<?php

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Conditions\NotEqualCondition;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;
use Mockery\MockInterface;

it('Apply', function () {
    $mockBuilder = Mockery::mock(Builder::class, function (MockInterface $mock) {
        $mock->shouldReceive('whereRaw')
            ->with('unaccent(?) != unaccent(?)', ['name', 'medeiroz'])
            ->once()
            ->andReturnSelf();
    });


    $filter = new Filter(
        'name',
        ColumnTypeEnum::STRING,
        ConditionEnum::NOT_EQUAL,
        'medeiroz',
    );

    $condition = new NotEqualCondition($filter);
    $resultBuilder = $condition->apply($mockBuilder);

    expect($resultBuilder)->toEqual($mockBuilder);
});
