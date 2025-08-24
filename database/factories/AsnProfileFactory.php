<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AsnProfile>
 */
class AsnProfileFactory extends Factory
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
            'full_name' => fake()->name(),
            'birth_date' => fake()->dateTimeBetween('-50 years', '-22 years')->format('Y-m-d'),
            'birth_place' => fake()->city(),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
            'position' => fake()->randomElement(['Staff', 'Supervisor', 'Manager', 'Kepala Seksi', 'Kepala Bidang']),
            'rank' => fake()->randomElement(['I/a', 'I/b', 'II/a', 'II/b', 'II/c', 'III/a', 'III/b', 'III/c', 'IV/a', 'IV/b']),
            'grade' => fake()->randomElement(['Juru', 'Juru Tingkat I', 'Pengatur', 'Pengatur Tingkat I', 'Penata Muda', 'Penata Muda Tingkat I', 'Penata', 'Pembina']),
            'appointment_date' => fake()->dateTimeBetween('-10 years', '-1 year')->format('Y-m-d'),
            'education_level' => fake()->randomElement(['SMA', 'D3', 'S1', 'S2', 'S3']),
            'major' => fake()->randomElement(['Administrasi Negara', 'Akuntansi', 'Teknik Sipil', 'Kedokteran', 'Pendidikan', 'Hukum', 'Ekonomi']),
        ];
    }
}