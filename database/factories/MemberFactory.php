<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'calendar_id' => Calendar::factory(),
            'name' => fake()->firstName(),
            'date_of_birth' => fake()->date(),
            'category' => fake()->randomElement(['person', 'pet', 'household']),
            'color' => fake()->hexColor(),
        ];
    }
}
