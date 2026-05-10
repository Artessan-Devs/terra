<?php

namespace ArtessanDevs\Terra\Models;

use ArtessanDevs\Terra\Models\Concerns\HasIdType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timezone extends Model
{
    use HasFactory;
    use HasIdType;

    protected $fillable = [
        'country_id',
        'zone_name',
        'gmt_offset',
        'gmt_offset_name',
        'abbreviation',
        'tz_name',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.country'));
    }
}
