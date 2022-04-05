<?php

namespace Medeiroz\LaravelDatatable\Entities;

use Illuminate\Database\Eloquent\Builder;

class EagerLoad
{
    public function __construct(
        public readonly string $name,
        public readonly array $columns,
    ) {
    }

    public function apply(Builder $builder): self
    {
        $eagerLoadNameWithColumns = $this->name . ':' . implode(',', $this->columns);
        $builder->with($eagerLoadNameWithColumns);

        return $this;
    }
}
