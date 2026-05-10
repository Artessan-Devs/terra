<?php

namespace ArtessanDevs\Terra\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Postcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'sync_id',
        'code',
        'country_id',
        'country_code',
        'state_id',
        'state_code',
        'city_id',
        'locality_name',
        'type',
        'latitude',
        'longitude',
        'source',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.country'));
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.state'));
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.city'));
    }
}
