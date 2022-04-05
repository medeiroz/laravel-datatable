<?php

use Medeiroz\LaravelDatatable\Conditions\ContainsCondition;
use Medeiroz\LaravelDatatable\Enums\ConditionEnum;

it('getClass', function () {
    $conditionEnum = ConditionEnum::CONTAINS;

    expect($conditionEnum->getClass())->toEqual(ContainsCondition::class);
});
