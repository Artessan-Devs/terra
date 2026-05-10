<?php

namespace ArtessanDevs\Terra\Database\Factories;

use ArtessanDevs\Terra\Models\Country;
use ArtessanDevs\Terra\Models\Postcode;
use ArtessanDevs\Terra\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostcodeFactory extends Factory
{
    protected $model = Postcode::class;

    public function definition(): array
    {
        return [
            'sync_id' => fake()->unique()->numberBetween(1, 1000000),
            'code' => fake()->postcode(),
            'country_id' => Country::factory(),
            'country_code' => fake()->countryCode(),
            'state_id' => State::factory(),
            'state_code' => fake()->word(),
        ];
    }
}
