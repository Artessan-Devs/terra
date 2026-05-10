<?php

namespace ArtessanDevs\Terra\Database\Factories;

use ArtessanDevs\Terra\Models\Country;
use ArtessanDevs\Terra\Models\Timezone;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimezoneFactory extends Factory
{
    protected $model = Timezone::class;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'zone_name' => fake()->timezone(),
            'gmt_offset' => fake()->numberBetween(-43200, 50400),
        ];
    }
}
