<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'linkable_type' => (new User())->getMorphClass(),
            'linkable_id' => User::factory(),
            'url' => $this->faker->url(),
            'description' => $this->faker->sentence(),
            'label' => $this->faker->word(),
        ];
    }
}
