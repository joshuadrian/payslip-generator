<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salary>
 */
class SalaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('2024-01-01 00:00:00', '2024-01-31 00:00:00');

        return [
            'amount' => rand(1, 100) * 1000000,
            'effective_date' => $date,
        ];
    }
}
