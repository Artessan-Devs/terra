<?php

namespace ArtessanDevs\Terra\Database\Factories;

use ArtessanDevs\Terra\Models\City;
use ArtessanDevs\Terra\Models\Country;
use ArtessanDevs\Terra\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'sync_id' => fake()->unique()->numberBetween(1, 1000000),
            'name' => $name,
            'localized_name' => ['en' => $name],
            'state_id' => State::factory(),
            'country_id' => Country::factory(),
            'country_code' => fake()->countryCode(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ];
    }
}
