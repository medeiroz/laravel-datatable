<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Medeiroz\LaravelDatatable\Entities\Column;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Entities\Sort;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;
use Medeiroz\LaravelDatatable\LaravelDatatable;
use Mockery\MockInterface;


it('Construct with Model', function () {
    $myModel = $this->getMockForAbstractClass(Model::class);
    $myDatatable = $this->getMockForAbstractClass(LaravelDatatable::class, [
        $myModel
    ]);

    expect($myDatatable)->toBeInstanceOf(LaravelDatatable::class);
});

it('Construct with Builder', function () {
    $myModel = $this->getMockForAbstractClass(Model::class);
    $myDatatable = $this->getMockForAbstractClass(LaravelDatatable::class, [
        $myModel->newModelQuery()
    ]);

    expect($myDatatable)->toBeInstanceOf(LaravelDatatable::class);
});


it('defaults', function () {
    $myModel = $this->getMockForAbstractClass(Model::class);
    $myDatatable = $this->getMockForAbstractClass(LaravelDatatable::class, [
        $myModel
    ]);

    expect($myDatatable->defaultFilters())->toEqual(collect());
    expect($myDatatable->defaultSort())->toEqual(collect([
        Sort::desc('updated_at'),
    ]));
    expect($myDatatable->defaultPerPage())->toEqual(config('datatable.per_page'));
    expect($myDatatable->perPage())->toEqual(config('datatable.per_page'));
});

it('getBuilder', function () {
    $myModel = $this->getMockForAbstractClass(Model::class);

    $myDatatable = new class($myModel) extends LaravelDatatable {
        public function columns(): Collection
        {
            return collect([
                Column::from('id', ColumnTypeEnum::NUMBER),
                Column::from('name', ColumnTypeEnum::STRING),
            ]);
        }
    };

    $resultBuilder = $myDatatable->getBuilder();

    expect($resultBuilder->getEagerLoads())->toEqual([]);
    expect($resultBuilder->getQuery()->wheres)->toEqual([]);
    expect($resultBuilder->getQuery()->orders)
        ->toEqual([
            [
                'column' => 'updated_at',
                'direction' => 'desc',
            ],
        ]);
    expect($resultBuilder->getQuery()->columns)
        ->toEqual([
            Str::of('id'),
            Str::of('name'),
        ]);
});

it('getBuilder with override default sort', function () {
    $myModel = $this->getMockForAbstractClass(Model::class);

    $myDatatable = new class($myModel) extends LaravelDatatable {
        public function columns(): Collection
        {
            return collect([
                Column::from('age', ColumnTypeEnum::NUMBER),
                Column::from('name', ColumnTypeEnum::STRING),
            ]);
        }

        public function defaultSort(): Collection
        {
            return collect([
                Sort::asc('name'),
                Sort::desc('age'),
            ]);
        }
    };

    $resultBuilder = $myDatatable->getBuilder();

    expect($resultBuilder->getEagerLoads())->toEqual([]);
    expect($resultBuilder->getQuery()->wheres)->toEqual([]);
    expect($resultBuilder->getQuery()->orders)
        ->toEqual([
            [
                'column' => 'name',
                'direction' => 'asc',
            ],
            [
                'column' => 'age',
                'direction' => 'desc',
            ],
        ]);
    expect($resultBuilder->getQuery()->columns)
        ->toEqual([
            Str::of('age'),
            Str::of('name'),
            Str::of('id'),
        ]);
});

it('getBuilder with sort by request', function () {
    $this->postJson('/', [
        'sort' => [
            ['email', 'asc'],
        ],
    ]);

    $myModel = $this->getMockForAbstractClass(Model::class);

    $myDatatable = new class($myModel) extends LaravelDatatable {
        public function columns(): Collection
        {
            return collect([
                Column::from('email', ColumnTypeEnum::STRING),
                Column::from('name', ColumnTypeEnum::STRING),
            ]);
        }
    };

    $resultBuilder = $myDatatable->getBuilder();

    expect($resultBuilder->getQuery()->orders)
        ->toEqual([
            [
                'column' => 'email',
                'direction' => 'asc',
            ],
        ]);
});

it('getBuilder with override default filter', function () {
    $myModel = $this->getMockForAbstractClass(Model::class);

    $myDatatable = new class($myModel) extends LaravelDatatable {
        public function columns(): Collection
        {
            return collect([
                Column::from('age', ColumnTypeEnum::NUMBER),
                Column::from('name', ColumnTypeEnum::STRING),
            ]);
        }

        public function defaultFilters(): Collection
        {
            return collect([
                new Filter('name', ColumnTypeEnum::STRING, ConditionEnum::CONTAINS, 'medeiroz'),
            ]);
        }
    };

    $resultBuilder = $myDatatable->getBuilder();

    expect($resultBuilder->getQuery()->wheres)
        ->toEqual([
            [
                'type' => 'raw',
                'sql' => 'unaccent(?) ILIKE unaccent(?)',
                'boolean' => 'and',
            ],
        ]);

    expect($resultBuilder->getQuery()->bindings['where'])
        ->toEqual([
            'name',
            '%medeiroz%'
        ]);
});


it('getBuilder with filter by request', function () {
    $this->postJson('/', [
        'filters' => [
            [
                'column' => 'age',
                'type' => ColumnTypeEnum::STRING->value,
                'condition' => ConditionEnum::CONTAINS->value,
                'value' => 24,
            ],
        ],
    ]);

    $myModel = $this->getMockForAbstractClass(Model::class);

    $myDatatable = new class($myModel) extends LaravelDatatable {
        public function columns(): Collection
        {
            return collect([
                Column::from('age', ColumnTypeEnum::NUMBER)->filterable(),
                Column::from('name', ColumnTypeEnum::STRING),
            ]);
        }
    };

    $resultBuilder = $myDatatable->getBuilder();

    expect($resultBuilder->getQuery()->wheres)
        ->toEqual([
            [
                'type' => 'raw',
                'sql' => 'unaccent(?) ILIKE unaccent(?)',
                'boolean' => 'and',
            ],
        ]);

    expect($resultBuilder->getQuery()->bindings['where'])
        ->toEqual([
            'age',
            '%24%'
        ]);
});
