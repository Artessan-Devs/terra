<?php

namespace ArtessanDevs\Terra\Database\Factories;

use ArtessanDevs\Terra\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'name' => fake()->currencyCode(),
            'code' => fake()->unique()->currencyCode(),
            'symbol' => fake()->randomElement(['$', '€', '£', '¥', '₽']),
        ];
    }
}
