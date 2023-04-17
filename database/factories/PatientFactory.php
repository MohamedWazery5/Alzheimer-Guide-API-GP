<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'Stage' => $this->faker->numberBetween(1, 7),
            'address' => $this->faker->address(),
            'birth_date' => $this->faker->date(),
            'phone' => $this->faker->phoneNumber(),
            "User_id" => $this->faker->unique()->numberBetween(6, 10),

        ];
    }
}
