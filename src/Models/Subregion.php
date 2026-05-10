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
class Subregion extends Model
{
    use HasFactory;
    use HasIdType;
    use HasTranslations;

    protected $fillable = [
        'sync_id',
        'name',
        'localized_name',
        'region_id',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(config('terra.models.region'));
    }

    public function countries(): HasMany
    {
        return $this->hasMany(config('terra.models.country'));
    }
}
