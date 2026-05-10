<?php

namespace ArtessanDevs\Terra\Models;

use ArtessanDevs\Terra\Models\Concerns\HasIdType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('localized_name')]
class State extends Model
{
    use HasFactory;
    use HasIdType;
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
        return $this->belongsTo(config('terra.models.country'));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.state'), 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(config('terra.models.state'), 'parent_id');
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
