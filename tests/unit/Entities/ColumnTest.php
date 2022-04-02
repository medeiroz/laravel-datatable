<?php

use Medeiroz\LaravelDatatable\Entities\Column;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;

it('from', function () {
    $column = Column::from('first_name', ColumnTypeEnum::STRING);

    expect($column->name)->toEqual('first_name');
    expect($column->getFullName())->toEqual('first_name');
});

it('filterable', function () {
    $column = Column::from('first_name', ColumnTypeEnum::STRING)
        ->filterable();

    expect($column->filterable)->toBeTruthy();
});

it('filterable falsy', function () {
    $column = Column::from('first_name', ColumnTypeEnum::STRING)
        ->filterable(false);

    expect($column->filterable)->toBeFalsy();
});
