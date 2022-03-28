<?php

namespace Medeiroz\LaravelDatatable\Entities;

use Illuminate\Support\Str;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Illuminate\Support\Stringable;

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
    public ?Stringable $link = null;

    final public function __construct(
        public Stringable $name,
        public readonly ColumnTypeEnum $type,
    )
    {
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

    public function link(string|Stringable $link): self
    {
        $this->link = Str::of($link);
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

    public function sortable(bool $sortable = true): self
    {
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
            $this->name
        ])->filter()->implode('.');

        return Str::of($fullName);
    }
}
