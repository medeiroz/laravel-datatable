<?php

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Conditions\LessOrEqualCondition;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;
use Mockery\MockInterface;

it('Apply', function () {
    $mockBuilder = Mockery::mock(Builder::class, function (MockInterface $mock) {
        $mock->shouldReceive('where')
            ->with('age', '<=', 50)
            ->once()
            ->andReturnSelf();
    });


    $filter = new Filter(
        'age',
        ColumnTypeEnum::NUMBER,
        ConditionEnum::LESS_OR_EQUAL,
        50,
    );

    $condition = new LessOrEqualCondition($filter);
    $resultBuilder = $condition->apply($mockBuilder);

    expect($resultBuilder)->toEqual($mockBuilder);
});
