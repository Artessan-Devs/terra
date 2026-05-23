# Laravel Terra — comprehensive world geodata

[![Latest Version on Packagist](https://img.shields.io/packagist/v/artessan-devs/terra.svg?style=flat-square)](https://packagist.org/packages/artessan-devs/terra)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/artessan-devs/terra/run-tests.yml?branch=development&label=tests&style=flat-square)](https://github.com/artessan-devs/terra/actions?query=workflow%3Arun-tests+branch%3Adevelopment)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/artessan-devs/terra/fix-php-code-style-issues.yml?branch=development&label=code%20style&style=flat-square)](https://github.com/artessan-devs/terra/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Adevelopment)
[![Total Downloads](https://img.shields.io/packagist/dt/artessan-devs/terra.svg?style=flat-square)](https://packagist.org/packages/artessan-devs/terra)

A Laravel package for countries, states, cities, currencies, postcodes, and timezones with multi-language support.

Data sourced from [dr5hn/countries-states-cities-database](https://github.com/dr5hn/countries-states-cities-database).

## Getting started

```bash
composer require artessan-devs/terra
php artisan vendor:publish --tag="terra-migrations"
php artisan migrate
php artisan terra:seed
```

```php
$country = Country::where('iso2', 'US')->first();
echo $country->localized_name; // United States
```

## Documentation

See the [docs index](docs/index.md) for installation, usage, models, architecture, and more.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## License

The MIT License (MIT). See [LICENSE](LICENSE.md).
