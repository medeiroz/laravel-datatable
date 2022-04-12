<?php

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Conditions\GreaterOrEqualCondition;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;
use Mockery\MockInterface;

it('Apply', function () {
    $mockBuilder = Mockery::mock(Builder::class, function (MockInterface $mock) {
        $mock->shouldReceive('where')
            ->with('age', '>=', 20)
            ->once()
            ->andReturnSelf();
    });


    $filter = new Filter(
        'age',
        ColumnTypeEnum::NUMBER,
        ConditionEnum::GREATER_OR_EQUAL,
        20,
    );

    $condition = new GreaterOrEqualCondition($filter);
    $resultBuilder = $condition->apply($mockBuilder);

    expect($resultBuilder)->toEqual($mockBuilder);
});
