<?php

namespace ArtessanDevs\Terra\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('localized_name')]
class City extends Model
{
    use HasFactory;
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
        return $this->belongsTo(State::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(City::class, 'parent_id');
    }
}
