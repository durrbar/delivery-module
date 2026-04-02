<?php

declare(strict_types=1);

namespace Modules\Delivery\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Delivery\Models\Shipping;

class ShippingClassFactory extends Factory
{
    protected $model = Shipping::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'amount' => $this->faker->randomFloat(2, 5, 100),
            'is_global' => $this->faker->boolean,
            'type' => $this->faker->randomElement(['fixed', 'percentage']),
            'created_at' => $this->faker->dateTimeBetween('-2 years'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }
}
