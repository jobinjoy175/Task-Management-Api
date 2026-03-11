<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProjectFactory extends Factory
{
    protected $model = \App\Models\Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'owner_id' => User::factory(), // creates a new user automatically
        ];
    }
}
