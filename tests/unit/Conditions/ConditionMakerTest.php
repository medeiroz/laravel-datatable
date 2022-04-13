<?php

use Medeiroz\LaravelDatatable\Conditions\ConditionMaker;
use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Conditions\EmptyCondition;
use Medeiroz\LaravelDatatable\Conditions\EndWithCondition;
use Medeiroz\LaravelDatatable\Conditions\EqualCondition;
use Medeiroz\LaravelDatatable\Conditions\GreaterCondition;
use Medeiroz\LaravelDatatable\Conditions\GreaterOrEqualCondition;
use Medeiroz\LaravelDatatable\Conditions\LessCondition;
use Medeiroz\LaravelDatatable\Conditions\LessOrEqualCondition;
use Medeiroz\LaravelDatatable\Conditions\NotContainsCondition;
use Medeiroz\LaravelDatatable\Conditions\NotEmptyCondition;
use Medeiroz\LaravelDatatable\Conditions\NotEndWithCondition;
use Medeiroz\LaravelDatatable\Conditions\NotEqualCondition;
use Medeiroz\LaravelDatatable\Conditions\NotStartWithCondition;
use Medeiroz\LaravelDatatable\Conditions\StartWithCondition;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;

it('contains', function () {
    $filter = new Filter(
        'first_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'medeiroz',
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(ContainsCondition::class);
});

it('empty', function () {
    $filter = new Filter(
        'first_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::EMPTY,
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(EmptyCondition::class);
});

it('end with', function () {
    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::END_WITH,
        'medeiroz'
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(EndWithCondition::class);
});

it('equal', function () {
    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::EQUAL,
        'flavio medeiroz'
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(EqualCondition::class);
});

it('greater', function () {
    $filter = new Filter(
        'age',
        ColumnTypeEnum::NUMBER,
        ConditionEnum::GREATER,
        24
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(GreaterCondition::class);
});

it('greater or equal', function () {
    $filter = new Filter(
        'age',
        ColumnTypeEnum::NUMBER,
        ConditionEnum::GREATER_OR_EQUAL,
        24
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(GreaterOrEqualCondition::class);
});

it('less', function () {
    $filter = new Filter(
        'age',
        ColumnTypeEnum::NUMBER,
        ConditionEnum::LESS,
        50
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(LessCondition::class);
});

it('less or equal', function () {
    $filter = new Filter(
        'age',
        ColumnTypeEnum::NUMBER,
        ConditionEnum::LESS_OR_EQUAL,
        50
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(LessOrEqualCondition::class);
});

it('not contains', function () {
    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::NOT_CONTAINS,
        'medeiroz'
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(NotContainsCondition::class);
});

it('not empty', function () {
    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::NOT_EMPTY,
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(NotEmptyCondition::class);
});

it('not end with', function () {
    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::NOT_END_WITH,
        'medeiroz'
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(NotEndWithCondition::class);
});

it('not equal', function () {
    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::NOT_EQUAL,
        'medeiroz'
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(NotEqualCondition::class);
});

it('not start with', function () {
    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::NOT_START_WITH,
        'flavio'
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(NotStartWithCondition::class);
});

it('start with', function () {
    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::START_WITH,
        'medeiroz'
    );

    $condition = ConditionMaker::make($filter);

    expect($condition)->toBeInstanceOf(StartWithCondition::class);
});
