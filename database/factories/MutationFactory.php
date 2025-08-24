<?php

namespace Database\Factories;

use App\Models\Opd;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mutation>
 */
class MutationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'mutation_number' => fake()->optional(0.6)->regexify('MUT-[0-9]{4}-[0-9]{6}'),
            'type' => fake()->randomElement(['transfer', 'promotion', 'demotion', 'secondment']),
            'from_opd_id' => Opd::factory(),
            'to_opd_id' => Opd::factory(),
            'current_position' => fake()->randomElement(['Staff', 'Supervisor', 'Manager', 'Kepala Seksi']),
            'proposed_position' => fake()->randomElement(['Supervisor', 'Manager', 'Kepala Seksi', 'Kepala Bidang']),
            'current_rank' => fake()->randomElement(['II/c', 'III/a', 'III/b', 'III/c']),
            'proposed_rank' => fake()->randomElement(['III/a', 'III/b', 'III/c', 'IV/a']),
            'reason' => fake()->paragraph(),
            'proposed_date' => fake()->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
            'status' => fake()->randomElement(['draft', 'submitted', 'opd_review', 'opd_approved', 'bkpsdm_review']),
        ];
    }

    /**
     * Indicate that the mutation is in draft status.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the mutation is submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
        ]);
    }

    /**
     * Indicate that the mutation is under OPD review.
     */
    public function opdReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'opd_review',
        ]);
    }

    /**
     * Indicate that the mutation is under BKPSDM review.
     */
    public function bkpsdmReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'bkpsdm_review',
            'opd_reviewed_by' => User::factory(),
            'opd_reviewed_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'opd_review_notes' => fake()->sentence(),
        ]);
    }
}