<?php

namespace ArtessanDevs\Terra\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ArtessanDevs\Terra\Terra
 */
class Terra extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ArtessanDevs\Terra\Terra::class;
    }
}
