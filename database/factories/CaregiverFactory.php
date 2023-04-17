<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Caregiver>
 */
class CaregiverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "Role"=>$this->faker->numberBetween(0,1),
            "User_id"=>$this->faker->unique()->numberBetween(1,5),
        ];
    }
}
