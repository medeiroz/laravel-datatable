<?php

use Illuminate\Database\Eloquent\Model;
use Medeiroz\LaravelDatatable\Entities\Sort;
use Medeiroz\LaravelDatatable\LaravelDatatable;

it('getDefaultFilters', function () {
    $myModel = new class extends Model {
    };

    $myDatatable = $this->getMockForAbstractClass(LaravelDatatable::class, [
        $myModel
    ]);

    expect($myDatatable->getDefaultFilters())->toEqual(collect());
    expect($myDatatable->defaultSort())->toEqual(collect([
        Sort::desc('updated_at'),
    ]));
    expect($myDatatable->defaultPerPage())->toEqual(config('datatable.per_page'));
    expect($myDatatable->perPage())->toEqual(config('datatable.per_page'));
});
