# Laravel Datatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/medeiroz/laravel-datatable.svg?style=flat-square)](https://packagist.org/packages/medeiroz/laravel-datatable)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/medeiroz/laravel-datatable/run-tests?label=tests)](https://github.com/medeiroz/laravel-datatable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/medeiroz/laravel-datatable/Check%20&%20fix%20styling?label=code%20style)](https://github.com/medeiroz/laravel-datatable/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/medeiroz/laravel-datatable.svg?style=flat-square)](https://packagist.org/packages/medeiroz/laravel-datatable)

This package provider datatable for Laravel application based model

## Installation

You can install the package via composer:

```bash
composer require medeiroz/laravel-datatable
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-datatable-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-datatable-views"
```

## Usage

```php
namespace App\Datatables\Partner;

use App\Models\Event\Event;
use App\Modules\Datatable\Column;
use App\Modules\Datatable\Datatable;
use App\Modules\Datatable\Enums\ColumnTypeEnum;
use App\Modules\Datatable\Link;
use Illuminate\Support\Collection;

class EventDatatable extends Datatable
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function columns(): Collection
    {
        return collect([
            Column::from('name', ColumnTypeEnum::STRING)
                ->label('Name')
                ->class('text-left')
                ->searchable()
                ->filterable()
                ->sortable()
                ->route('users.show'), // route name

            Column::from('address.city', ColumnTypeEnum::STRING)
                ->label('Address')
                ->class('text-left')
                ->searchable()
                ->filterable()
                ->sortable()
                ->route('users.addresses.show'),

            Column::from('updated_at', ColumnTypeEnum::DATE)
                ->label('Last Update')
                ->class('text-left')
                ->sortable(),
        ]);
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Credits

- [Flavio Medeiros](https://github.com/medeiroz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
