<?php

namespace ArtessanDevs\Terra\Database\Seeders;

use Kdabrow\SeederOnce\SeederOnce;

class RegionSeeder extends SeederOnce
{
    public function __construct()
    {
        $this->seedOnce = config('terra.seed_once', true);
    }

    public function run(): void
    {
        $regions = json_decode(
            file_get_contents(__DIR__.'/../Datasets/regions/regions.json'), true
        );

        $model = config('terra.models.region');

        foreach ($regions as $data) {
            $model::create([
                'sync_id' => $data['id'],
                'name' => $data['name'],
                'localized_name' => $this->parseTranslations($data),
            ]);
        }

        $this->command->info('Regions seeded: '.count($regions));
    }

    protected function parseTranslations(array $data): array
    {
        return array_merge(
            ['en' => $data['name']],
            $data['translations'] ?? []
        );
    }
}
