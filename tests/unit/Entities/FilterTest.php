<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;
use Mockery\MockInterface;

it('Make', function () {
    $filter = new Filter(
        'age',
        ColumnTypeEnum::NUMBER,
        ConditionEnum::CONTAINS,
        24,
    );

    expect($filter->getColumn())->toEqual('age');
    expect($filter->getType())->toEqual(ColumnTypeEnum::NUMBER);
    expect($filter->getCondition())->toEqual(ConditionEnum::CONTAINS);
    expect($filter->getValue())->toEqual(24);
    expect($filter->getRelationship())->toBeNull();
});

it('SetColumn', function () {
    $filter = new Filter(
        'address.street',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'Av. Paulista',
    );

    expect($filter->getColumn())->toEqual('street');
    expect($filter->getType())->toEqual(ColumnTypeEnum::STRING);
    expect($filter->getCondition())->toEqual(ConditionEnum::CONTAINS);
    expect($filter->getValue())->toEqual('Av. Paulista');
    expect($filter->getRelationship())->toEqual('address');
});

it('setType with Enum', function () {
    $filter = new Filter(
        'test_set_type_with_enum',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'my_enum',
    );

    expect($filter->getColumn())->toEqual('test_set_type_with_enum');
    expect($filter->getType())->toEqual(ColumnTypeEnum::STRING);
    expect($filter->getCondition())->toEqual(ConditionEnum::CONTAINS);
    expect($filter->getValue())->toEqual('my_enum');
    expect($filter->getRelationship())->toBeNull();
});

it('setType with string', function () {
    $filter = new Filter(
        'test_set_type_with_string',
        'string',
        ConditionEnum::CONTAINS,
        'my_string',
    );

    expect($filter->getColumn())->toEqual('test_set_type_with_string');
    expect($filter->getType())->toEqual(ColumnTypeEnum::STRING);
    expect($filter->getCondition())->toEqual(ConditionEnum::CONTAINS);
    expect($filter->getValue())->toEqual('my_string');
    expect($filter->getRelationship())->toBeNull();
});

it('setCondition with Enum', function () {
    $filter = new Filter(
        'test_set_condition_with_enum',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'my_enum',
    );

    expect($filter->getColumn())->toEqual('test_set_condition_with_enum');
    expect($filter->getType())->toEqual(ColumnTypeEnum::STRING);
    expect($filter->getCondition())->toEqual(ConditionEnum::CONTAINS);
    expect($filter->getValue())->toEqual('my_enum');
    expect($filter->getRelationship())->toBeNull();
});

it('setCondition with string', function () {
    $filter = new Filter(
        'test_set_condition_with_string',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'my_string',
    );

    expect($filter->getColumn())->toEqual('test_set_condition_with_string');
    expect($filter->getType())->toEqual(ColumnTypeEnum::STRING);
    expect($filter->getCondition())->toEqual(ConditionEnum::CONTAINS);
    expect($filter->getValue())->toEqual('my_string');
    expect($filter->getRelationship())->toBeNull();
});

it('setValue String', function ($value, $expected) {
    $filter = new Filter(
        'set_value',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        $value,
    );

    expect($filter->getValue())->toBeString();
    expect($filter->getValue())->toEqual($expected);
})->with([
    ['example', 'example'],
    [null, '',],
    [10, '10',],
]);

it('setValue Boolean', function ($value, $expected) {
    $filter = new Filter(
        'set_value',
        ColumnTypeEnum::BOOLEAN,
        ConditionEnum::CONTAINS,
        $value,
    );

    expect($filter->getValue())->toBeBool();
    expect($filter->getValue())->toEqual($expected);
})->with([
    [true, true],
    [false, false],
    [1, true],
    [0, false],
    [null, false],
    ['', false],
]);

it('setValue Number', function ($value, $expected) {
    $filter = new Filter(
        'set_value',
        ColumnTypeEnum::NUMBER,
        ConditionEnum::CONTAINS,
        $value,
    );

    expect($filter->getValue())->toBeNumeric();
    expect($filter->getValue())->toEqual($expected);
})->with([
    [10, 10],
    ['20', 20],
    [30.45, 30.45],
    [null, 0],
    ['', 0],
]);

it('setValue date', function ($value, $expected) {
    $filter = new Filter(
        'set_value',
        ColumnTypeEnum::DATE,
        ConditionEnum::CONTAINS,
        $value,
    );

    $expectedTimeStringDate = (string) Carbon::parse($expected)->setTime(0, 0);

    expect($filter->getValue())->toBeInstanceOf(Carbon::class);
    expect($filter->getValue())->toEqual($expected);
    expect((string) $filter->getValue())->toEqual($expectedTimeStringDate);
})->with([
    ['2022-03-31', Carbon::parse('2022-03-31', 'utc')],
    [Carbon::parse('2022-04-01 15:25', 'utc'), Carbon::parse('2022-04-01', 'utc')],
]);

it('setValue date time', function ($value, $expected) {
    $filter = new Filter(
        'set_value',
        ColumnTypeEnum::DATE_TIME,
        ConditionEnum::CONTAINS,
        $value,
    );

    expect($filter->getValue())->toBeInstanceOf(Carbon::class);
    expect($filter->getValue())->toEqual($expected);
})->with([
    ['2022-03-31 12:32', Carbon::parse('2022-03-31 12:32', 'utc')],
    [Carbon::parse('2022-04-01 05:41', 'utc'), Carbon::parse('2022-04-01 05:41', 'utc')],
]);


it('apply without relationship', function () {
    $mockBuilder = Mockery::mock(Builder::class);

    $mockCondition = Mockery::mock(ContainsCondition::class, function (MockInterface $mock) use ($mockBuilder) {
        $mock->shouldReceive('apply')
            ->with($mockBuilder)
            ->once()
            ->andReturn($mockBuilder);
    });

    $this->app->bind(
        ContainsCondition::class,
        fn () => $mockCondition
    );

    $filter = new Filter(
        'full_name',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'medeiroz',
    );

    $filter->apply($mockBuilder);
});

it('apply with relationship', function () {
    $mockBuilder = Mockery::mock(Builder::class, function (MockInterface $mock) {
        $mock->shouldReceive('whereHas')
            ->once()
            ->with('address', Mockery::on(fn (Closure $callback) => true));
    });

    $mockCondition = Mockery::mock(ContainsCondition::class, function (MockInterface $mock) use ($mockBuilder) {
        $mock->shouldReceive('apply')
            ->with($mockBuilder)
            ->once()
            ->andReturn($mockBuilder);
    });

    $this->app->bind(
        ContainsCondition::class,
        fn () => $mockCondition
    );

    $filter = new Filter(
        'address.street',
        ColumnTypeEnum::STRING,
        ConditionEnum::CONTAINS,
        'Av. Paulista',
    );

    $filter->apply($mockBuilder);
});
