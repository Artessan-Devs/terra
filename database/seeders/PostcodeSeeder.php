<?php

namespace ArtessanDevs\Terra\Database\Seeders;

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

        $countryModel = config('terra.models.country');
        $stateModel = config('terra.models.state');
        $cityModel = config('terra.models.city');
        $model = config('terra.models.postcode');

        $countryMap = $countryModel::pluck('id', 'sync_id');
        $stateMap = $stateModel::pluck('id', 'sync_id');
        $cityMap = $cityModel::pluck('id', 'sync_id');

        $total = 0;

        foreach (glob($datasetsDir.'*.json') as $file) {
            $postcodes = json_decode(file_get_contents($file), true);
            if (empty($postcodes)) {
                continue;
            }

            foreach (array_chunk($postcodes, 500) as $chunk) {
                $inserts = [];
                foreach ($chunk as $data) {
                    $inserts[] = [
                        'sync_id' => $data['id'],
                        'code' => $data['code'],
                        'country_id' => $countryMap[$data['country_id']],
                        'country_code' => $data['country_code'],
                        'state_id' => $stateMap[$data['state_id']],
                        'state_code' => $data['state_code'],
                        'city_id' => isset($data['city_id']) ? ($cityMap[$data['city_id']] ?? null) : null,
                        'locality_name' => $data['locality_name'] ?? null,
                        'type' => $data['type'] ?? null,
                        'latitude' => isset($data['latitude']) ? (string) $data['latitude'] : null,
                        'longitude' => isset($data['longitude']) ? (string) $data['longitude'] : null,
                        'source' => $data['source'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $total++;
                }
                $model::insert($inserts);
            }
        }

        $this->command->info('Postcodes seeded: '.$total);
    }
}
