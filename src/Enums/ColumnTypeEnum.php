<?php

namespace Medeiroz\LaravelDatatable\Enums;

enum ColumnTypeEnum: string
{
    case STRING = 'string';
    case NUMBER = 'number';
    case DATE = 'date';
    case DATE_TIME = 'date_time';
    case BOOLEAN = 'boolean';
}
