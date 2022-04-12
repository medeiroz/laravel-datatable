<?php

namespace Medeiroz\LaravelDatatable\Enums;

use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Conditions\EmptyCondition;
use Medeiroz\LaravelDatatable\Conditions\EndWithCondition;
use Medeiroz\LaravelDatatable\Conditions\EqualCondition;
use Medeiroz\LaravelDatatable\Conditions\GreaterCondition;
use Medeiroz\LaravelDatatable\Conditions\GreaterOrEqualCondition;
use Medeiroz\LaravelDatatable\Conditions\LessCondition;
use Medeiroz\LaravelDatatable\Conditions\LessOrEqualCondition;
use Medeiroz\LaravelDatatable\Conditions\NotContainsCondition;
use Medeiroz\LaravelDatatable\Conditions\NotEmptyCondition;
use Medeiroz\LaravelDatatable\Conditions\NotEndWithCondition;
use Medeiroz\LaravelDatatable\Conditions\NotEqualCondition;
use Medeiroz\LaravelDatatable\Conditions\NotStartWithCondition;
use Medeiroz\LaravelDatatable\Conditions\StartWithCondition;

enum ConditionEnum: string
{
    case CONTAINS = 'contains';
    case START_WITH = 'start_with';
    case END_WITH = 'end_with';
    case NOT_CONTAINS = 'not_contains';
    case NOT_START_WITH = 'not_start_with';
    case NOT_END_WITH = 'not_end_with';
    case EQUAL = 'equal';
    case NOT_EQUAL = 'not_equal';
    case GREATER = 'greater';
    case GREATER_OR_EQUAL = 'greater_or_equal';
    case LESS = 'less';
    case LESS_OR_EQUAL = 'less_or_equal';
    case EMPTY = 'empty';
    case NOT_EMPTY = 'not_empty';

    public function getClass(): String
    {
        return match ($this) {
            self::CONTAINS => ContainsCondition::class,
            self::START_WITH => StartWithCondition::class,
            self::END_WITH => EndWithCondition::class,
            self::NOT_CONTAINS => NotContainsCondition::class,
            self::NOT_START_WITH => NotStartWithCondition::class,
            self::NOT_END_WITH => NotEndWithCondition::class,
            self::EQUAL => EqualCondition::class,
            self::NOT_EQUAL => NotEqualCondition::class,
            self::GREATER => GreaterCondition::class,
            self::GREATER_OR_EQUAL => GreaterOrEqualCondition::class,
            self::LESS => LessCondition::class,
            self::LESS_OR_EQUAL => LessOrEqualCondition::class,
            self::EMPTY => EmptyCondition::class,
            self::NOT_EMPTY => NotEmptyCondition::class,
        };
    }
}
