<?php

namespace ArtessanDevs\Terra\Database\Seeders;

use Kdabrow\SeederOnce\SeederOnce;

class CountrySeeder extends SeederOnce
{
    public function __construct()
    {
        $this->seedOnce = config('terra.seed_once', true);
    }

    public function run(): void
    {
        $countries = json_decode(
            file_get_contents(__DIR__.'/../Datasets/countries/countries.json'), true
        );

        $regionModel = config('terra.models.region');
        $subregionModel = config('terra.models.subregion');
        $currencyModel = config('terra.models.currency');
        $timezoneModel = config('terra.models.timezone');
        $model = config('terra.models.country');

        $regionMap = $regionModel::pluck('id', 'sync_id');
        $subregionMap = $subregionModel::pluck('id', 'sync_id');

        foreach ($countries as $data) {
            $currency = null;
            if (! empty($data['currency'])) {
                $currency = $currencyModel::firstOrCreate(
                    ['code' => $data['currency']],
                    [
                        'name' => $data['currency_name'] ?? null,
                        'symbol' => $data['currency_symbol'] ?? null,
                    ]
                );
            }

            $country = $model::create([
                'sync_id' => $data['id'],
                'name' => $data['name'],
                'localized_name' => $this->parseTranslations($data),
                'iso2' => $data['iso2'],
                'iso3' => $data['iso3'],
                'numeric_code' => $data['numeric_code'],
                'phonecode' => $data['phonecode'],
                'capital' => $data['capital'] ?? null,
                'currency_id' => $currency?->id,
                'tld' => $data['tld'] ?? null,
                'native' => $data['native'] ?? null,
                'population' => $data['population'] ?? null,
                'gdp' => $data['gdp'] ?? null,
                'region_id' => isset($data['region_id']) ? ($regionMap[$data['region_id']] ?? null) : null,
                'subregion_id' => isset($data['subregion_id']) ? ($subregionMap[$data['subregion_id']] ?? null) : null,
                'nationality' => $data['nationality'] ?? null,
                'area_sq_km' => $data['area_sq_km'] ?? null,
                'postal_code_format' => $data['postal_code_format'] ?? null,
                'postal_code_regex' => $data['postal_code_regex'] ?? null,
                'latitude' => isset($data['latitude']) ? (string) $data['latitude'] : null,
                'longitude' => isset($data['longitude']) ? (string) $data['longitude'] : null,
                'emoji' => $data['emoji'] ?? null,
                'emoji_u' => $data['emojiU'] ?? null,
            ]);

            if (! empty($data['timezones'])) {
                foreach ($data['timezones'] as $tzData) {
                    $timezoneModel::create([
                        'country_id' => $country->id,
                        'zone_name' => $tzData['zoneName'],
                        'gmt_offset' => $tzData['gmtOffset'],
                        'gmt_offset_name' => $tzData['gmtOffsetName'] ?? null,
                        'abbreviation' => $tzData['abbreviation'] ?? null,
                        'tz_name' => $tzData['tzName'] ?? null,
                    ]);
                }
            }
        }

        $this->command->info('Countries seeded: '.count($countries));
    }

    protected function parseTranslations(array $data): array
    {
        return array_merge(
            ['en' => $data['name']],
            $data['translations'] ?? []
        );
    }
}
