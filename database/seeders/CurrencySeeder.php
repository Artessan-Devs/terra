<?php

namespace ArtessanDevs\Terra\Database\Seeders;

use Kdabrow\SeederOnce\SeederOnce;

class CurrencySeeder extends SeederOnce
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

        $model = config('terra.models.currency');

        $seen = [];
        foreach ($countries as $data) {
            if (empty($data['currency']) || isset($seen[$data['currency']])) {
                continue;
            }
            $seen[$data['currency']] = true;

            $model::create([
                'name' => $data['currency_name'] ?? null,
                'code' => $data['currency'],
                'symbol' => $data['currency_symbol'] ?? null,
            ]);
        }

        $this->command->info('Currencies seeded: '.count($seen));
    }
}
