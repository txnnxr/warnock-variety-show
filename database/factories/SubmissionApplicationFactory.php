<?php

namespace Database\Factories;

use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubmissionApplication>
 */
class SubmissionApplicationFactory extends Factory
{
    public function definition()
    {
        return [
            'show_id' => Show::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('555-###-####'),
            'title' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'approved' => false,
        ];
    }
}
