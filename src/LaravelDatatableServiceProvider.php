<?php

namespace Medeiroz\LaravelDatatable;

use Medeiroz\LaravelDatatable\Commands\LaravelDatatableCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDatatableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-datatable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-datatable_table')
            ->hasCommand(LaravelDatatableCommand::class);
    }
}
