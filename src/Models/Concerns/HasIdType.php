<?php

namespace ArtessanDevs\Terra\Models\Concerns;

use Illuminate\Support\Str;

trait HasIdType
{
    public function initializeHasIdType(): void
    {
        $idType = config('terra.id_type', 'id');

        if ($idType !== 'id') {
            $this->incrementing = false;
            $this->keyType = 'string';
        }
    }

    protected static function bootHasIdType(): void
    {
        $idType = config('terra.id_type', 'id');

        if ($idType === 'id') {
            return;
        }

        static::creating(function ($model) use ($idType) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = match ($idType) {
                    'uuid-v7' => (string) Str::uuid7(),
                    'ulid' => (string) Str::ulid(),
                    default => (string) Str::uuid(),
                };
            }
        });
    }
}
