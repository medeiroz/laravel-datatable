<?php

use Medeiroz\LaravelDatatable\Conditions\ConditionMaker;
use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;

it('make', function () {
    $filter = new Filter(
        'first_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'medeiroz',
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(ContainsCondition::class);
});
