<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-30 days', 'now');
        $checkIn = fake()->time('H:i', '09:00');
        $checkOut = fake()->optional(0.8)->time('H:i', '17:00');

        return [
            'user_id' => User::factory(),
            'date' => $date->format('Y-m-d'),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'status' => fake()->randomElement(['present', 'absent', 'late', 'sick', 'leave']),
            'notes' => fake()->optional(0.3)->sentence(),
            'approval_status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }

    /**
     * Indicate that the attendance is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'approval_notes' => null,
        ]);
    }

    /**
     * Indicate that the attendance is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'approved',
            'approved_by' => User::factory(),
            'approved_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'approval_notes' => fake()->optional(0.5)->sentence(),
        ]);
    }

    /**
     * Indicate that the attendance is present.
     */
    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'present',
        ]);
    }
}