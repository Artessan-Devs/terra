# AGENTS.md — terra

Laravel geodata package (`artessan-devs/terra`). Built with `spatie/laravel-package-tools` and `spatie/laravel-translatable`.

## Dev setup

```bash
composer install              # then post-autoload-dump runs testbench package:discover
composer prepare              # manual equivalent (if hook skipped)
```

## Commands

| Command | Tool |
|---|---|
| `composer test` | Pest 4.x (via Orchestra Testbench, SQLite in-memory) |
| `composer analyse` | PHPStan level 5 (src/ + config/ + database/) |
| `composer format` | Laravel Pint |
| `php artisan terra:seed` | Import geodata from `database/Datasets/` JSON files |
| `vendor/bin/pest --ci` | CI test invocation (adds --ci flag) |
| `vendor/bin/phpstan --error-format=github` | CI analysis |

## Architecture

- **Namespace**: `ArtessanDevs\Terra\` (src/), `ArtessanDevs\Terra\Tests\` (tests/)
- **Service provider**: `ArtessanDevs\Terra\TerraServiceProvider` — registers config (`terra`), views, all 6 migrations, and `terra:seed` Artisan command
- **Facade**: `ArtessanDevs\Terra\Facades\Terra` — alias `Terra`
- **Artisan command**: `terra:seed` — calls `TerraDatabaseSeeder`
- **Config tag**: `terra-config`, **migrations tag**: `terra-migrations`, **views tag**: `terra-views`

## Models (src/Models/)

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

All models use `sync_id` (integer, unique) to store the original JSON `id` field. The real PK (`id`) is free for auto-increment, UUID v4/v7, or ULID — controlled by `config('terra.id_type')`.

Model classes are configurable via `config('terra.models.*')`. Override any model with a subclass to add `HasUuids` or `HasUlids` traits for non-auto-increment PKs.

Translatable models use the `#[Translatable('localized_name')]` attribute (Spatie v6+).
`name` is the plain string official name; `localized_name` (JSON) holds all locale translations.

## Factories (database/factories/)

One factory per model, all use `HasFactory` trait on the model. Factory names follow `guessFactoryNamesUsing` convention already set in `TestCase`.

## Seeders (database/seeders/)

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

## Database migrations

Eight publishable migrations (registered via `hasMigrations` in service provider):
`create_regions_table`, `create_subregions_table`, `create_countries_table`, `create_states_table`, `create_cities_table`, `create_postcodes_table`, `create_timezones_table`, `create_currencies_table`.

All use `sync_id` (unique integer), `flag` (boolean), timestamps. Translatable `localized_name` columns are `json`. The PK column and all FK columns use the type from `config('terra.id_type')` — `$table->id()` / `foreignId()`, `$table->uuid()` / `foreignUuid()`, or `$table->ulid()` / `foreignUlid()`.

## Test quirks

- Tests use Pest + Orchestra Testbench. `phpunit.xml.dist` is present but `composer test` runs Pest directly.
- **Migrations are NOT auto-loaded in tests** (the `foreach` in `TestCase::getEnvironmentSetUp` is commented out). If you add models requiring tables, uncomment or use `$this->loadMigrationsFrom()`.
- Test DB is `testing` (SQLite in-memory), set in `TestCase::getEnvironmentSetUp`.

## CI matrix

PHP 8.3–8.5, Laravel 12.*/13.*, prefer-lowest + prefer-stable, ubuntu + windows. Test timeout: 5 min.

## PHPStan

Level 5, analyses `src/`, `config/`, `database/`. Baseline at `phpstan-baseline.neon` (currently empty). Tmp dir: `build/phpstan`.

## Namespace note

Composer PSR-4 uses `ArtessanDevs\Terra\` (capital D). All source files must match this casing exactly — PHPStan enforces it.
