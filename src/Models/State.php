<?php

namespace ArtessanDevs\Terra\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('localized_name')]
class State extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'sync_id',
        'name',
        'localized_name',
        'country_id',
        'country_code',
        'fips_code',
        'iso2',
        'iso3166_2',
        'type',
        'level',
        'parent_id',
        'native',
        'latitude',
        'longitude',
        'timezone',
        'population',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(State::class, 'parent_id');
    }
}
