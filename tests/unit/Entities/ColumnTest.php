<?php

use Medeiroz\LaravelDatatable\Entities\Column;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;

it('from', function () {
    $column = Column::from('first_name', ColumnTypeEnum::STRING);

    expect($column->name)->toEqual('first_name');
    expect($column->getFullName())->toEqual('first_name');
    /** defaults */
    expect($column->filterable)->toBeFalsy();
    expect($column->searchable)->toBeFalsy();
    expect($column->sortable)->toBeFalsy();
    expect($column->hidden)->toBeFalsy();
    expect($column->refs)->toEqual([]);
    expect($column->label)->toBeNull();
    expect($column->relationship)->toBeNull();
    expect($column->relationship)->toBeNull();
    expect($column->class)->toBeNull();
    expect($column->route)->toBeNull();
});

it('label', function () {
    $column = Column::from('first_name', ColumnTypeEnum::STRING)
        ->label('First Name');

    expect($column->label)->toEqual('First Name');
});

it('class', function () {
    $column = Column::from('last_name', ColumnTypeEnum::STRING)
        ->class('text-bold text-left');

    expect($column->class)->toEqual('text-bold text-left');
});

it('route', function () {
    $column = Column::from('id', ColumnTypeEnum::STRING)
        ->route('users.show');

    expect($column->route)->toEqual('users.show');
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

it('searchable', function () {
    $column = Column::from('first_name', ColumnTypeEnum::STRING)
        ->searchable();

    expect($column->searchable)->toBeTruthy();
});

it('searchable falsy', function () {
    $column = Column::from('first_name', ColumnTypeEnum::STRING)
        ->searchable(false);

    expect($column->searchable)->toBeFalsy();
});

it('hidden', function () {
    $column = Column::from('address.id', ColumnTypeEnum::NUMBER)
        ->hidden();

    expect($column->hidden)->toBeTruthy();
});

it('hidden falsy', function () {
    $column = Column::from('address.id', ColumnTypeEnum::NUMBER)
        ->hidden(false);

    expect($column->hidden)->toBeFalsy();
});

it('sortable', function () {
    $column = Column::from('updated_at', ColumnTypeEnum::DATE_TIME)
        ->sortable();

    expect($column->sortable)->toBeTruthy();
});

it('sortable falsy', function () {
    $column = Column::from('updated_at', ColumnTypeEnum::DATE_TIME)
        ->sortable(false);

    expect($column->sortable)->toBeFalsy();
});

it('refs', function () {
    $column = Column::from('first_and_last_name', ColumnTypeEnum::STRING)
        ->refs(['first_name', 'last_name']);

    expect($column->refs)->toEqual(['first_name', 'last_name']);
});

it('getFullName', function () {
    $column = Column::from('age', ColumnTypeEnum::NUMBER);

    expect($column->getFullName())->toEqual('age');
});

it('getFullName with relationship', function () {
    $column = Column::from('address.street', ColumnTypeEnum::NUMBER);

    expect($column->getFullName())->toEqual('address.street');
});
