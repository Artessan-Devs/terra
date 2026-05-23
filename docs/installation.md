# Installation

```bash
composer require artessan-devs/terra
```

Publish and run migrations:

```bash
php artisan vendor:publish --tag="terra-migrations"
php artisan migrate
```

Publish the config:

```bash
php artisan vendor:publish --tag="terra-config"
```

## Usage

Seed the geodata:

```bash
php artisan terra:seed
```

Query models:

```php
use App\Models\Country;

$countries = Country::where('iso2', 'US')->first();
echo $countries->name; // United States
echo $countries->translation('name', 'es'); // Estados Unidos
```

### Choosing a primary key type

Set `TERRA_ID_TYPE=uuid-v4` in your `.env` before running migrations. Extend the model classes to add `HasUuids` / `HasUlids` traits, then register them in `config('terra.models.*')`.

## Testing

```bash
composer test
```
