<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certificate>
 */
class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'ISO '.fake()->numberBetween(9000, 45000).' Certificate',
            'issuer' => fake()->company(),
            'file_path' => 'certificates/'.fake()->uuid().'.pdf',
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
