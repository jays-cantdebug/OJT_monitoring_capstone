<?php

namespace Database\Factories;

use App\Enums\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'student_intern',
            // Fixed default (not random) so a factory-created Dean and a
            // factory-created Student land in the same department unless a
            // test explicitly overrides it - keeps every pre-existing test
            // that pairs a Dean with students working under department
            // scoping without needing changes.
            'department' => Department::IT,
        ];
    }

    /**
     * Indicate that the user belongs to a specific department.
     */
    public function department(Department $department): static
    {
        return $this->state(fn (array $attributes) => [
            'department' => $department,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a Dean.
     */
    public function dean(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'dean',
        ]);
    }

    /**
     * Indicate that the user is an Admin - no department, distinct from
     * Dean and Student Intern.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'department' => null,
        ]);
    }

    /**
     * Indicate that the user is a self-registered account awaiting
     * Dean approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the user's self-registration was rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}
