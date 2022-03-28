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
use Medeiroz\LaravelDatatable\Entities\Sort;

abstract class LaravelDatatable
{
    public function __construct(protected readonly Model $model)
    {
    }

    abstract public function columns(): Collection;

    public function getDefaultFilters(): Collection
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
        $builder = $this->model
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
        $filters = collect(
            request()->input('filters', [])
        );

        if ($filters->isNotEmpty()) {
            return $filters
                ->filter(function (array $filter) {
                    $columnName = Str::of($filter['column'])->lower();
                    $column = $this->columns()
                        ->first(fn (Column $column) => $column->getFullName()->lower()->exactly($columnName));

                    return ($column && $column->filterable);
                })
                ->map(function (array $filter) {
                    return app()->makeWith(Filter::class, $filter);
                });
        }

        return $this->getDefaultFilters();
    }

    private function getSorts(): Collection
    {
        if (request()->has('sort')) {
            return collect(
                request()->input('sort', [])
            )
                ->map(fn (array $sort) => new Sort($sort[0], $sort[1]));
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
                $columns = $columns->push('id');

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
            ->push('id')
            ->concat($relationshipsNames);
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
        $filters->each(fn (Filter $filter) => $filter->apply($builder));

        return $builder;
    }
}
