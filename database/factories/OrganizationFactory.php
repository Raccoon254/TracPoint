<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => $this->faker->company,
            'description'       => $this->faker->optional()->paragraph,
            'address'           => $this->faker->address,
            'logo'              => $this->faker->imageUrl(400, 400, 'business', true),
            'website_url'       => $this->faker->url,
            'verification_code' => Str::random(10),
        ];
    }
}
