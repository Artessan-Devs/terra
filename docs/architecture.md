# Architecture

- **Namespace**: `ArtessanDevs\Terra\` (src/), `ArtessanDevs\Terra\Tests\` (tests/)
- **Service provider**: `ArtessanDevs\Terra\TerraServiceProvider` — registers config (`terra`), views, all 6 migrations, and `terra:seed` Artisan command
- **Facade**: `ArtessanDevs\Terra\Facades\Terra` — alias `Terra`
- **Artisan command**: `terra:seed` — calls `TerraDatabaseSeeder`
- **Config tag**: `terra-config`
- **Migrations tag**: `terra-migrations`
- **Views tag**: `terra-views`

## Seeders

Seeders read from `database/Datasets/` and must be run in dependency order:

1. `RegionSeeder` → `regions/regions.json`
2. `SubregionSeeder` → `subregions/subregions.json`
3. `CountrySeeder` → `countries/countries.json`
4. `StateSeeder` → `states/states.json`
5. `CitySeeder` → `cities/*.json` (per-country files)
6. `PostcodeSeeder` → `postcodes/*.json`

The `TerraDatabaseSeeder` orchestrates all six. Run via `php artisan terra:seed`.

All seeders extend `Kdabrow\SeederOnce\SeederOnce`, skipping previously-seeded classes on re-runs. Disable via `config('terra.seed_once')` or set it in your `.env` with `TERRA_SEED_ONCE=false`.

During country seeding, `Currency` and `Timezone` records are extracted from the country JSON data (no separate dataset files).

**Key behavior**: The JSON `name` field is stored as-is in the `name` column. The JSON `translations` object is merged with `name` (as `en`) into the Spatie Translatable `localized_name` column. Foreign keys (`region_id`, `country_id`, etc. in JSON) are `sync_id` references — seeders resolve them to auto-increment IDs via lookup maps. Seeders resolve all model classes from config, so custom subclasses are respected.

## Migrations

Eight publishable migrations:
`create_regions_table`, `create_subregions_table`, `create_countries_table`, `create_states_table`, `create_cities_table`, `create_postcodes_table`, `create_timezones_table`, `create_currencies_table`.

All use `sync_id` (unique integer), `flag` (boolean), timestamps. Translatable `localized_name` columns are `json`. The PK column and all FK columns use the type from `config('terra.id_type')` — `$table->id()` / `foreignId()`, `$table->uuid()` / `foreignUuid()`, or `$table->ulid()` / `foreignUlid()`.
