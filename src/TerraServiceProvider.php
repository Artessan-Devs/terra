<?php

namespace ArtessanDevs\Terra;

use ArtessanDevs\Terra\Commands\TerraCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TerraServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('terra')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations(
                'create_regions_table',
                'create_subregions_table',
                'create_currencies_table',
                'create_countries_table',
                'create_states_table',
                'create_cities_table',
                'create_timezones_table',
                'create_postcodes_table',
            )
            ->hasCommand(TerraCommand::class);
    }
}
