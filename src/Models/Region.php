<?php

namespace ArtessanDevs\Terra\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Translatable('localized_name')]
class Region extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'sync_id',
        'name',
        'localized_name',
    ];
}
