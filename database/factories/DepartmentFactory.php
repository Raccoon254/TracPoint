<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Choose a common department name from a preset list.
            'name'           => $this->faker->randomElement([
                'Human Resources',
                'Finance',
                'Information Technology',
                'Marketing',
                'Sales'
            ]),
            // Generate a department code in the format "DEP" followed by three digits.
            'code'           => $this->faker->bothify('DEP###'),
            // For hierarchical departments, you can later override this value.
            'parent_id'      => null,
            // Generate a random city name as the department's location.
            'location'       => $this->faker->city,
            // Generate a random floor number (converted to string to match the migration).
            'floor'          => (string) $this->faker->numberBetween(1, 10),
            // Randomly pick a building name.
            'building'       => $this->faker->randomElement([
                'Building A',
                'Building B',
                'Building C',
                'Main Building'
            ]),
            // No manager is assigned by default.
            'manager_id'     => null,
            // Associate this department with an organization.
            // Note: Ensure your Department model allows mass assignment of organization_id or assign it in a callback.
            'organization_id'=> Organization::factory(),
        ];
    }
}
