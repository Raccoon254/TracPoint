<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Organization;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => $this->faker->name,
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),

            // Additional fields defined in your migration/model:
            'role'              => $this->faker->randomElement(['super_admin', 'admin', 'auditor', 'user']),
            'position'          => $this->faker->jobTitle,
            'employee_id'       => $this->faker->unique()->numerify('EMP####'),
            'phone'             => $this->faker->phoneNumber,
            'status'            => $this->faker->randomElement(['active', 'inactive']),
            // Note: We do not set organization_id and department_id here so that
            // they can be created (or overridden) later via the configure() callback.
        ];
    }

    /**
     * Configure the factory to automatically create and associate an organization
     * and a department (belonging to that organization) after creating a user.
     *
     * @return static
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $allOrganizations = Organization::all();
            $allDepartments = Department::all();

            // If the user does not have an organization, create one.
            if (empty($user->organization_id)) {
                $organization = $allOrganizations->random();
                $user->organization()->associate($organization);
                $user->save();
            }

            // If the user does not have a department, create one that belongs to the user's organization.
            if (empty($user->department_id) && !empty($user->organization_id)) {
                $department = $allDepartments->where('organization_id', $user->organization_id)->random();
                $user->department()->associate($department);
                $user->save();
            }
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
