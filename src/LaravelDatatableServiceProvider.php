<?php

namespace Medeiroz\LaravelDatatable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Medeiroz\LaravelDatatable\Commands\LaravelDatatableCommand;

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
