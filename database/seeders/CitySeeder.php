<?php

namespace ArtessanDevs\Terra\Database\Seeders;

use Illuminate\Support\Str;
use Kdabrow\SeederOnce\SeederOnce;

class CitySeeder extends SeederOnce
{
    public function __construct()
    {
        $this->seedOnce = config('terra.seed_once', true);
    }

    public function run(): void
    {
        $datasetsDir = __DIR__.'/../Datasets/cities/';

        $files = glob($datasetsDir.'*.json');
        $allowed = config('terra.allowed_countries', []);
        if (! empty($allowed)) {
            $files = array_values(array_filter($files, fn ($f) => in_array(pathinfo($f, PATHINFO_FILENAME), $allowed)));
        }

        $idType = config('terra.id_type', 'id');
        $countryModel = config('terra.models.country');
        $stateModel = config('terra.models.state');
        $model = config('terra.models.city');

        $countryMap = $countryModel::pluck('id', 'sync_id');
        $stateMap = $stateModel::pluck('id', 'sync_id');

        $total = 0;

        foreach ($files as $file) {
            $cities = json_decode(file_get_contents($file), true);
            if (empty($cities)) {
                continue;
            }

            foreach (array_chunk($cities, 500) as $chunk) {
                $inserts = [];
                foreach ($chunk as $data) {
                    $row = [
                        'sync_id' => $data['id'] ?? null,
                        'name' => $data['name'],
                        'localized_name' => json_encode($this->parseTranslations($data), JSON_UNESCAPED_UNICODE),
                        'state_id' => $stateMap[$data['state_id']] ?? null,
                        'state_code' => $data['state_code'] ?? null,
                        'country_id' => $countryMap[$data['country_id']],
                        'country_code' => $data['country_code'],
                        'type' => $data['type'] ?? null,
                        'level' => $data['level'] ?? null,
                        'latitude' => (string) $data['latitude'],
                        'longitude' => (string) $data['longitude'],
                        'native' => $data['native'] ?? null,
                        'population' => $data['population'] ?? null,
                        'timezone' => $data['timezone'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if ($idType !== 'id') {
                        $row['id'] = $this->generateId($idType);
                    }

                    $inserts[] = $row;
                    $total++;
                }
                $model::insert($inserts);
            }

            $citySyncMap = $model::whereIn('sync_id', array_column($cities, 'id'))
                ->pluck('id', 'sync_id');
            foreach ($cities as $data) {
                if (! empty($data['parent_id']) && isset($citySyncMap[$data['parent_id']])) {
                    $model::where('sync_id', $data['id'])
                        ->update(['parent_id' => $citySyncMap[$data['parent_id']]]);
                }
            }
        }

        $this->command->info('Cities seeded: '.$total);
    }

    protected function generateId(string $type): string
    {
        return match ($type) {
            'uuid-v7' => (string) Str::uuid7(),
            'ulid' => (string) Str::ulid(),
            default => (string) Str::uuid(),
        };
    }

    protected function parseTranslations(array $data): array
    {
        return array_merge(
            ['en' => $data['name']],
            $data['translations'] ?? []
        );
    }
}
