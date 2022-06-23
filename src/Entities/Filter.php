<?php

namespace Medeiroz\LaravelDatatable\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Conditions\ConditionMaker;
use Medeiroz\LaravelDatatable\Enums\ColumnTypeEnum;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;
use Medeiroz\LaravelDatatable\Enums\GroupConditionEnum;

class Filter
{
    private string $column;
    private ColumnTypeEnum $type;
    private ConditionEnum $condition;
    private string|int|float|bool|Carbon|null $value;
    private GroupConditionEnum $groupCondition;
    private string|null $relationship = null;

    public function __construct(
        string                              $column,
        string|ColumnTypeEnum               $type,
        string|ConditionEnum                $condition,
        string|int|float|bool|Carbon|null   $value = null,
        string|GroupConditionEnum $groupCondition = GroupConditionEnum::_AND,
    ) {
        $this->setColumn($column);
        $this->setType($type);
        $this->setCondition($condition);
        $this->setValue($value);
        $this->setGroupCondition($groupCondition);
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

    public function getValue(): string|int|float|bool
    {
        return $this->value;
    }

    public function setValue(null|string|int|float|bool|Carbon $value): self
    {
        try {
            $this->value = match ($this->getType()) {
                ColumnTypeEnum::STRING => (string) $value,
                ColumnTypeEnum::BOOLEAN => (bool) $value,
                ColumnTypeEnum::NUMBER => (float) $value,
                ColumnTypeEnum::DATE => (string) $value,
                ColumnTypeEnum::DATE_TIME => (string) $value,
            };
        } catch (\Throwable $exception) {
            $this->value = $value;
        }

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

    public function getGroupCondition(): GroupConditionEnum
    {
        return $this->groupCondition;
    }

    public function setGroupCondition(string|GroupConditionEnum $groupCondition): self
    {
        $this->groupCondition = ($groupCondition instanceof GroupConditionEnum)
            ? $groupCondition
            : GroupConditionEnum::from($groupCondition);

        return $this;
    }

    public function apply(Builder $builder): self
    {
        if ($this->getRelationship()) {
            $closure = function (Builder $relationshipBuilder) {
                $relationshipBuilder->where(function (Builder $whereBuilder) {
                    $this->applyCondition($whereBuilder);
                });
            };

            if ($this->getGroupCondition() === GroupConditionEnum::_AND) {
                $builder->whereHas(
                    $this->getRelationship(),
                    $closure
                );
            } elseif ($this->getGroupCondition() === GroupConditionEnum::_OR) {
                $builder->orWhereHas(
                    $this->getRelationship(),
                    $closure
                );
            }
        } else {
            $this->applyCondition($builder);
        }

        return $this;
    }

    protected function applyCondition(Builder $builder): Builder
    {
        $conditionInstance = ConditionMaker::make($this);
        return $conditionInstance->apply($builder);
    }
}
