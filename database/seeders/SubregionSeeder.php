<?php

namespace ArtessanDevs\Terra\Database\Seeders;

use Kdabrow\SeederOnce\SeederOnce;

class SubregionSeeder extends SeederOnce
{
    public function __construct()
    {
        $this->seedOnce = config('terra.seed_once', true);
    }

    public function run(): void
    {
        $subregions = json_decode(
            file_get_contents(__DIR__.'/../Datasets/subregions/subregions.json'), true
        );

        $regionModel = config('terra.models.region');
        $model = config('terra.models.subregion');

        $regionMap = $regionModel::pluck('id', 'sync_id');

        foreach ($subregions as $data) {
            $model::create([
                'sync_id' => $data['id'],
                'name' => $data['name'],
                'localized_name' => $this->parseTranslations($data),
                'region_id' => $regionMap[$data['region_id']],
            ]);
        }

        $this->command->info('Subregions seeded: '.count($subregions));
    }

    protected function parseTranslations(array $data): array
    {
        return array_merge(
            ['en' => $data['name']],
            $data['translations'] ?? []
        );
    }
}
