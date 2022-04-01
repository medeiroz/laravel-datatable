<?php

namespace Medeiroz\LaravelDatatable\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Medeiroz\LaravelDatatable\LaravelDatatableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Medeiroz\\LaravelDatatable\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDatatableServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('datatable.per_page', 15);

        $app['config']['app.timezone'] = 'utc';
    }
}
