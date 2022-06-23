<?php

namespace Medeiroz\LaravelDatatable;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Medeiroz\LaravelDatatable\Entities\Column;
use Medeiroz\LaravelDatatable\Entities\EagerLoad;
use Medeiroz\LaravelDatatable\Entities\Filter;
use Medeiroz\LaravelDatatable\Entities\FilterGroup;
use Medeiroz\LaravelDatatable\Entities\Sort;
use Medeiroz\LaravelDatatable\Enums\GroupConditionEnum;

abstract class LaravelDatatable
{
    protected readonly Builder|Model $builder;

    public function __construct(
        Builder|Model $builder,
    ) {
        $this->builder = ($builder instanceof Builder)
            ? $builder
            : $builder->newModelQuery();
    }

    abstract public function columns(): Collection;

    public abstract function routes(): Collection;

    public function defaultFilters(): Collection
    {
        return collect();
    }

    public function defaultSort(): Collection
    {
        return collect([
            Sort::desc('updated_at'),
        ]);
    }

    public function defaultPerPage(): int
    {
        return config('datatable.per_page');
    }

    public function perPage(): int
    {
        return request()->input('per_page', $this->defaultPerPage());
    }

    public function getBuilder(): Builder
    {
        $columns = $this->getColumnNamesRaw()->toArray();

        /** @var Builder $builder */
        $builder = $this->builder
            ->clone()
            ->select($columns);

        $this->applyEagerLoads($builder, $this->getEagerLoads());
        $this->applySorts($builder, $this->getSorts());
        $this->applyFilters($builder, $this->getFilters());

        return $builder;
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->getBuilder()
            ->paginate($this->perPage())
            ->withQueryString()
            ->onEachSide(0);
    }

    private function getFilters(): Collection
    {
        $filters = $this->defaultFilters();
        $termFilter = $this->getFiltersByTerm(request()->input('term'));

        $filtersRequest = collect(
            request()->input('filters', [])
        );

        if ($filtersRequest->isNotEmpty()) {
            $filters = $filtersRequest
                ->filter(function (array $filter) {
                    $columnName = Str::of($filter['column'])->lower();
                    $column = $this->columns()
                        ->first(fn (Column $column) => $column->getFullName()->lower()->exactly($columnName));

                    return ($column && $column->filterable);
                })
                ->map(fn (array $filter) => app()->makeWith(Filter::class, $filter));
        }

        return $filters->push($termFilter);
    }

    private function getSorts(): Collection
    {
        if (request()->has('sort')) {
            return collect(
                request()->input('sort')
            )
                ->map(fn (array $sort) => Sort::by($sort[0], $sort[1]));
        }

        return $this->defaultSort();
    }

    private function getColumnsOnlyWithRelationship(): Collection
    {
        return $this->columns()
            ->filter(fn (Column $column) => $column->relationship && $column->relationship->isNotEmpty());
    }

    private function groupRelationships(): Collection
    {
        $relationships = $this->getColumnsOnlyWithRelationship();

        if ($relationships->isNotEmpty()) {
            return $relationships->groupBy(fn (Column $column) => (string) $column->relationship);
        }

        return collect();
    }

    private function getEagerLoads(): Collection
    {
        return $this->groupRelationships()
            ->map(function (Collection $group, string $groupName) {
                $columns = $group->map(fn (Column $column) => $column->name);
                $columns = $columns->push(Str::of('id'));

                return new EagerLoad($groupName, $columns->toArray());
            });
    }

    private function getColumnNamesRaw(): Collection
    {
        $relationshipsNames = $this->groupRelationships()
            ->keys()
            ->map(fn (string $name) => Str::of($name)->singular() . '_id');

        return $this->columns()
            ->filter(fn (Column $column) => ! $column->relationship || $column->relationship->isEmpty())
            ->map(fn (Column $column) => $column->name)
            ->push(Str::of('id'))
            ->concat($relationshipsNames)
            ->unique();
    }

    private function applyEagerLoads(Builder $builder, Collection $eagerLoads): Builder
    {
        $eagerLoads->each(fn (EagerLoad $eagerLoad) => $eagerLoad->apply($builder));

        return $builder;
    }

    private function applySorts(Builder $builder, Collection $sorts): Builder
    {
        $sorts->each(fn (Sort $sort) => $sort->apply($builder));

        return $builder;
    }

    private function applyFilters(Builder $builder, Collection $filters): Builder
    {
        $filters->each(function (Filter|FilterGroup $filter) use ($builder) {
            $filter->apply($builder);
        });

        return $builder;
    }

    private function getFiltersByTerm(?string $term): FilterGroup
    {
        $filters = collect();

        if ($term) {
            $filters = $this->columns()
                ->filter(fn(Column $column) => $column->filterable)
                ->map(fn(Column $column) => $column->makeTermFilter($term));
        }

        return new FilterGroup($filters, GroupConditionEnum::_AND);
    }
}
