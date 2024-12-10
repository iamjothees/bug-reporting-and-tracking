<?php

namespace Database\Factories;

use App\Models\Bug;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BugHistory>
 */
class BugHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bug_id' => fake()->boolean(30) ? Bug::factory() : Bug::inRandomOrder()->first()->id,
            'description' => $this->faker->paragraph(4),
            'updater_id' => fake()->boolean(30) ? User::factory() : User::inRandomOrder()->first()->id,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
