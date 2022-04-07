<?php

namespace Medeiroz\LaravelDatatable\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Exceptions\RelationshipColumnUnsortable;

class Column
{
    public bool $filterable = false;
    public bool $searchable = false;
    public bool $sortable = false;
    public bool $hidden = false;
    public array $refs = [];
    public ?Stringable $label = null;
    public ?Stringable $relationship = null;
    public ?Stringable $class = null;
    public ?Stringable $route = null;

    final public function __construct(
        public Stringable $name,
        public readonly ColumnTypeEnum $type,
    ) {
        if ($name->contains('.')) {
            $this->name = $name->afterLast('.');
            $this->relationship = $name->before('.');
        }
    }

    public static function from(string $name, ColumnTypeEnum $type): static
    {
        return new static(Str::of($name), $type);
    }

    public function label(string|Stringable $label): self
    {
        $this->label = Str::of($label);

        return $this;
    }

    public function class(string|Stringable $class): self
    {
        $this->class = Str::of($class);

        return $this;
    }

    public function route(string|Stringable $route): self
    {
        $this->route = Str::of($route);

        return $this;
    }

    public function filterable(bool $filterable = true): self
    {
        $this->filterable = $filterable;

        return $this;
    }

    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function hidden(bool $hidden = true): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @throws RelationshipColumnUnsortable
     */
    public function sortable(bool $sortable = true): self
    {
        if (
            $this->hasRelationship()
            && $sortable
        ) {
            throw new RelationshipColumnUnsortable();
        }

        $this->sortable = $sortable;

        return $this;
    }

    public function refs(array $columnsName): self
    {
        $this->refs = $columnsName;

        return $this;
    }

    public function getFullName(): Stringable
    {
        $fullName = collect([
            $this->relationship,
            $this->name,
        ])->filter()->implode('.');

        return Str::of($fullName);
    }

    public function hasRelationship(): bool
    {
        return $this->relationship && $this->relationship->isNotEmpty();
    }
}
