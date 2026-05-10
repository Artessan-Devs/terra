<?php

namespace ArtessanDevs\Terra\Database\Factories;

use ArtessanDevs\Terra\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'sync_id' => fake()->unique()->numberBetween(1, 1000),
            'name' => $name,
            'localized_name' => ['en' => $name],
        ];
    }
}
