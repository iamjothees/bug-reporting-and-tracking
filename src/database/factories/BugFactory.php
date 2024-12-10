<?php

namespace Database\Factories;

use App\BugSeverity;
use App\BugStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bug>
 */
class BugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(4),
            'severity' => $this->faker->randomElement(BugSeverity::cases()),
            'status' => $this->faker->randomElement(BugStatus::cases()),
            'reporter_id' => User::factory(),
            'assignee_id' => fake()->boolean(30) ? User::factory() : null,
        ];;
    }
}
