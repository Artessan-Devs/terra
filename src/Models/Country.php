<?php

namespace ArtessanDevs\Terra\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('localized_name')]
class Country extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'sync_id',
        'name',
        'localized_name',
        'iso2',
        'iso3',
        'numeric_code',
        'phonecode',
        'capital',
        'currency_id',
        'tld',
        'native',
        'population',
        'gdp',
        'region_id',
        'subregion_id',
        'nationality',
        'area_sq_km',
        'postal_code_format',
        'postal_code_regex',
        'latitude',
        'longitude',
        'emoji',
        'emoji_u',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.region'));
    }

    public function subregion(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.subregion'));
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.currency'));
    }

    public function timezones(): HasMany
    {
        return $this->hasMany(config('terra.models.timezone'));
    }

    public function states(): HasMany
    {
        return $this->hasMany(config('terra.models.state'));
    }

    public function cities(): HasMany
    {
        return $this->hasMany(config('terra.models.city'));
    }

    public function postcodes(): HasMany
    {
        return $this->hasMany(config('terra.models.postcode'));
    }
}
