<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    protected $model = Enquiry::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => EnquiryType::Product,
            'status' => EnquiryStatus::New,
            'product_name' => ucwords(fake()->words(3, true)),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '+91 98765 43210',
            'company' => fake()->company(),
            'city' => fake()->city(),
            'message' => fake()->sentence(),
        ];
    }

    public function contact(): static
    {
        return $this->state(['type' => EnquiryType::Contact, 'product_name' => null, 'subject' => 'General question']);
    }
}
