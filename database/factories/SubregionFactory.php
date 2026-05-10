<?php

namespace ArtessanDevs\Terra\Database\Factories;

use ArtessanDevs\Terra\Models\Region;
use ArtessanDevs\Terra\Models\Subregion;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubregionFactory extends Factory
{
    protected $model = Subregion::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'sync_id' => fake()->unique()->numberBetween(1, 1000),
            'name' => $name,
            'localized_name' => ['en' => $name],
            'region_id' => Region::factory(),
        ];
    }
}
