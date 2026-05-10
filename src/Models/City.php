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
class City extends Model
{
    use HasFactory;
    use HasIdType;
    use HasTranslations;

    protected $fillable = [
        'sync_id',
        'name',
        'localized_name',
        'state_id',
        'state_code',
        'country_id',
        'country_code',
        'type',
        'level',
        'parent_id',
        'latitude',
        'longitude',
        'native',
        'population',
        'timezone',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.state'));
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.country'));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.city'), 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(config('terra.models.city'), 'parent_id');
    }

    public function postcodes(): HasMany
    {
        return $this->hasMany(config('terra.models.postcode'));
    }
}
