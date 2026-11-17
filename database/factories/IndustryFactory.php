<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FeatureIcon;
use App\Models\Industry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Industry>
 */
class IndustryFactory extends Factory
{
    protected $model = Industry::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucwords(fake()->unique()->words(2, true)),
            'description' => fake()->sentence(8),
            'icon' => fake()->randomElement(FeatureIcon::cases()),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
