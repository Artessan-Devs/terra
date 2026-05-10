<?php

namespace ArtessanDevs\Terra\Commands;

use ArtessanDevs\Terra\Database\Seeders\TerraDatabaseSeeder;
use Illuminate\Console\Command;

class TerraCommand extends Command
{
    public $signature = 'terra:seed';

    public $description = 'Seed geodata from dataset files';

    public function handle(): int
    {
        $this->comment('Seeding geodata...');

        $this->call(TerraDatabaseSeeder::class);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
