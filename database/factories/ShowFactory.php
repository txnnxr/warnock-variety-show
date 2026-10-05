<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'date' => now()->addWeeks(2)->setTime(20, 0),
            'max_attendants' => 30,
            'address' => $this->faker->numberBetween(1, 999).' Warnock Lane',
        ];
    }
}
