<?php

namespace ArtessanDevs\Terra\Database\Seeders;

use Kdabrow\SeederOnce\SeederOnce;

class TerraDatabaseSeeder extends SeederOnce
{
    public function __construct()
    {
        $this->seedOnce = config('terra.seed_once', true);
    }

    public function run(): void
    {
        $this->call(RegionSeeder::class);
        $this->call(SubregionSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(PostcodeSeeder::class);
    }
}
