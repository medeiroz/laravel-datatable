<?php

namespace Medeiroz\LaravelDatatable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Medeiroz\LaravelDatatable\LaravelDatatable
 */
class LaravelDatatable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-datatable';
    }
}
