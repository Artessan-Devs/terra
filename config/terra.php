<?php

use ArtessanDevs\Terra\Models\City;
use ArtessanDevs\Terra\Models\Country;
use ArtessanDevs\Terra\Models\Currency;
use ArtessanDevs\Terra\Models\Postcode;
use ArtessanDevs\Terra\Models\Region;
use ArtessanDevs\Terra\Models\State;
use ArtessanDevs\Terra\Models\Subregion;
use ArtessanDevs\Terra\Models\Timezone;

return [
    'id_type' => 'id',

    'seed_once' => true,

    'models' => [
        'region' => Region::class,
        'subregion' => Subregion::class,
        'country' => Country::class,
        'state' => State::class,
        'city' => City::class,
        'postcode' => Postcode::class,
        'currency' => Currency::class,
        'timezone' => Timezone::class,
    ],
];
