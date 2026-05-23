# Models

| Model | Table | Translatable | Relations |
|---|---|---|---|---|
| `Region` | `regions` | `localized_name` | `hasMany(Subregion)`, `hasMany(Country)` |
| `Subregion` | `subregions` | `localized_name` | `belongsTo(Region)`, `hasMany(Country)` |
| `Country` | `countries` | `localized_name` | `belongsTo(Region)`, `belongsTo(Subregion)`, `belongsTo(Currency)`, `hasMany(Timezone)`, `hasMany(State)`, `hasMany(City)`, `hasMany(Postcode)` |
| `State` | `states` | `localized_name` | `belongsTo(Country)`, `belongsTo(State, parent_id)`, `hasMany(State, children)`, `hasMany(City)`, `hasMany(Postcode)` |
| `City` | `cities` | `localized_name` | `belongsTo(State)`, `belongsTo(Country)`, `belongsTo(City, parent_id)`, `hasMany(City, children)`, `hasMany(Postcode)` |
| `Currency` | `currencies` | — | `hasMany(Country)` |
| `Timezone` | `timezones` | — | `belongsTo(Country)` |
| `Postcode` | `postcodes` | — | `belongsTo(Country)`, `belongsTo(State)`, `belongsTo(City)` |

## Primary keys

All models use `sync_id` (integer, unique) to store the original JSON `id` field. The real PK (`id`) is free for auto-increment, UUID v4/v7, or ULID — controlled by `config('terra.id_type')`.

Model classes are configurable via `config('terra.models.*')`. Override any model with a subclass to add `HasUuids` or `HasUlids` traits for non-auto-increment PKs. Bulk seeders (states, cities, postcodes) generate IDs inline for UUID/ULID types since `::insert()` bypasses Eloquent events.

Translatable models use the `#[Translatable('localized_name')]` attribute (Spatie v6+).
`name` is the plain string official name; `localized_name` (JSON) holds all locale translations.
