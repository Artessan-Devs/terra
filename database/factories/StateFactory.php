<?php

namespace ArtessanDevs\Terra\Database\Factories;

use ArtessanDevs\Terra\Models\Country;
use ArtessanDevs\Terra\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'sync_id' => fake()->unique()->numberBetween(1, 100000),
            'name' => $name,
            'localized_name' => ['en' => $name],
            'country_id' => Country::factory(),
            'country_code' => fake()->countryCode(),
        ];
    }
}
