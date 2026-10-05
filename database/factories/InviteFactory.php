<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'show_id' => \App\Models\Show::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'key' => (string) \Illuminate\Support\Str::uuid(),
            'response_status' => 'CREATED',
        ];
    }

    public function attending(bool $plusOne = false)
    {
        return $this->state(['response_status' => 'ATTENDING', 'plus_one_status' => $plusOne]);
    }
}
