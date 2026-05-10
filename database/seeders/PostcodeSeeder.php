<?php

namespace ArtessanDevs\Terra\Database\Seeders;

use Illuminate\Support\Str;
use Kdabrow\SeederOnce\SeederOnce;

class PostcodeSeeder extends SeederOnce
{
    public function __construct()
    {
        $this->seedOnce = config('terra.seed_once', true);
    }

    public function run(): void
    {
        $datasetsDir = __DIR__.'/../Datasets/postcodes/';

        $files = glob($datasetsDir.'*.json');
        $allowed = config('terra.allowed_countries', []);
        if (! empty($allowed)) {
            $files = array_values(array_filter($files, fn ($f) => in_array(pathinfo($f, PATHINFO_FILENAME), $allowed)));
        }

        $idType = config('terra.id_type', 'id');
        $countryModel = config('terra.models.country');
        $stateModel = config('terra.models.state');
        $cityModel = config('terra.models.city');
        $model = config('terra.models.postcode');

        $countryMap = $countryModel::pluck('id', 'sync_id');
        $stateMap = $stateModel::pluck('id', 'sync_id');
        $cityMap = $cityModel::pluck('id', 'sync_id');

        $total = 0;

        foreach ($files as $file) {
            $postcodes = json_decode(file_get_contents($file), true);
            if (empty($postcodes)) {
                continue;
            }

            foreach (array_chunk($postcodes, 500) as $chunk) {
                $inserts = [];
                foreach ($chunk as $data) {
                    $row = [
                        'sync_id' => $data['id'] ?? null,
                        'code' => $data['code'],
                        'country_id' => $countryMap[$data['country_id']],
                        'country_code' => $data['country_code'],
                        'state_id' => isset($data['state_id']) ? ($stateMap[$data['state_id']] ?? null) : null,
                        'state_code' => $data['state_code'] ?? null,
                        'city_id' => isset($data['city_id']) ? ($cityMap[$data['city_id']] ?? null) : null,
                        'locality_name' => $data['locality_name'] ?? null,
                        'type' => $data['type'] ?? null,
                        'latitude' => isset($data['latitude']) ? (string) $data['latitude'] : null,
                        'longitude' => isset($data['longitude']) ? (string) $data['longitude'] : null,
                        'source' => $data['source'] ?? null,
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
        }

        $this->command->info('Postcodes seeded: '.$total);
    }

    protected function generateId(string $type): string
    {
        return match ($type) {
            'uuid-v7' => (string) Str::uuid7(),
            'ulid' => (string) Str::ulid(),
            default => (string) Str::uuid(),
        };
    }
}
