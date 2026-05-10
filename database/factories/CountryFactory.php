<?php

namespace ArtessanDevs\Terra\Database\Factories;

use ArtessanDevs\Terra\Models\Country;
use ArtessanDevs\Terra\Models\Region;
use ArtessanDevs\Terra\Models\Subregion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        $name = fake()->unique()->country();

        return [
            'sync_id' => fake()->unique()->numberBetween(1, 1000),
            'name' => $name,
            'localized_name' => ['en' => $name],
            'iso2' => fake()->unique()->countryCode(),
            'iso3' => fake()->unique()->regexify('[A-Z]{3}'),
            'numeric_code' => fake()->numerify('###'),
            'phonecode' => fake()->numerify('###'),
            'region_id' => Region::factory(),
            'subregion_id' => Subregion::factory(),
        ];
    }
}
