<?php

namespace Medeiroz\LaravelDatatable\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\ConditionMaker;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;

class Filter
{
    private string $column;
    private ColumnTypeEnum $type;
    private ConditionEnum $condition;
    private string|int|float|bool|Carbon|null $value;
    private string|null $relationship = null;

    public function __construct(
        string                              $column,
        string|ColumnTypeEnum               $type,
        string|ConditionEnum                $condition,
        string|int|float|bool|Carbon|null   $value = null,
    ) {
        $this->setColumn($column);
        $this->setType($type);
        $this->setCondition($condition);
        $this->setValue($value);
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function setColumn(string $column): self
    {
        if (str_contains($column, '.')) {
            $parts = explode('.', $column);
            $this->column = $parts[1];
            $this->setRelationship($parts[0]);
        } else {
            $this->column = $column;
        }

        return $this;
    }

    public function getType(): ColumnTypeEnum
    {
        return $this->type;
    }

    public function setType(string|ColumnTypeEnum $type): self
    {
        $this->type = ($type instanceof ColumnTypeEnum)
            ? $type
            : ColumnTypeEnum::from($type);

        return $this;
    }

    public function getCondition(): ConditionEnum
    {
        return $this->condition;
    }

    public function setCondition(string|ConditionEnum $condition): self
    {
        $this->condition = ($condition instanceof ConditionEnum)
            ? $condition
            : ConditionEnum::from($condition);

        return $this;
    }

    public function getValue(): string|int|float|bool|Carbon
    {
        return $this->value;
    }

    public function setValue(string|int|float|bool|Carbon|null $value): self
    {
        $this->value = match ($this->getType()) {
            ColumnTypeEnum::STRING => (string) $value,
            ColumnTypeEnum::BOOLEAN => (bool) $value,
            ColumnTypeEnum::NUMBER => (float) $value,
            ColumnTypeEnum::DATE => Carbon::parse($value)->setTime(0, 0),
            ColumnTypeEnum::DATE_TIME => Carbon::parse($value),
        };

        return $this;
    }

    public function getRelationship(): ?string
    {
        return $this->relationship;
    }

    protected function setRelationship(string $relationship): self
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function apply(Builder $builder): self
    {
        if ($this->getRelationship()) {
            $builder->whereHas(
                $this->getRelationship(),
                fn (Builder $relationshipBuilder) => $this->apply($relationshipBuilder)
            );
        }

        $this->applyCondition($builder);

        return $this;
    }

    protected function applyCondition(Builder $builder): Builder
    {
        $conditionInstance = ConditionMaker::make($this);

        return $conditionInstance->apply($builder);
    }
}
