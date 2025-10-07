<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => fake()->sentence(),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'view_count' => fake()->numberBetween(0, 1000),
            'estimated_cost' => fake()->numberBetween(1000, 10000),
            'pinned' => fake()->boolean(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
