<?php

namespace ArtessanDevs\Terra\Database\Seeders;

use Illuminate\Support\Str;
use Kdabrow\SeederOnce\SeederOnce;

class StateSeeder extends SeederOnce
{
    public function __construct()
    {
        $this->seedOnce = config('terra.seed_once', true);
    }

    public function run(): void
    {
        $states = json_decode(
            file_get_contents(__DIR__.'/../Datasets/states/states.json'), true
        );

        $idType = config('terra.id_type', 'id');
        $countryModel = config('terra.models.country');
        $model = config('terra.models.state');

        $countryMap = $countryModel::pluck('id', 'sync_id');

        $chunks = array_chunk($states, 500);
        foreach ($chunks as $chunk) {
            $inserts = [];
            foreach ($chunk as $data) {
                $row = [
                    'sync_id' => $data['id'],
                    'name' => $data['name'],
                    'localized_name' => json_encode($this->parseTranslations($data), JSON_UNESCAPED_UNICODE),
                    'country_id' => $countryMap[$data['country_id']],
                    'country_code' => $data['country_code'],
                    'fips_code' => $data['fips_code'] ?? null,
                    'iso2' => $data['iso2'] ?? null,
                    'iso3166_2' => $data['iso3166_2'] ?? null,
                    'type' => $data['type'] ?? null,
                    'level' => $data['level'] ?? null,
                    'native' => $data['native'] ?? null,
                    'latitude' => isset($data['latitude']) ? (string) $data['latitude'] : null,
                    'longitude' => isset($data['longitude']) ? (string) $data['longitude'] : null,
                    'timezone' => $data['timezone'] ?? null,
                    'population' => $data['population'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($idType !== 'id') {
                    $row['id'] = $this->generateId($idType);
                }

                $inserts[] = $row;
            }
            $model::insert($inserts);
        }

        $stateMap = $model::pluck('id', 'sync_id');
        $updates = [];
        foreach ($states as $data) {
            if (! empty($data['parent_id']) && isset($stateMap[$data['parent_id']])) {
                $updates[$stateMap[$data['id']]] = $stateMap[$data['parent_id']];
            }
        }
        foreach ($updates as $id => $parentId) {
            $model::where('id', $id)->update(['parent_id' => $parentId]);
        }

        $this->command->info('States seeded: '.count($states));
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
