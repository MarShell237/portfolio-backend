<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fileable_id' => \App\Models\User::factory(),
            'fileable_type' => 'App\Models\User',
            'file_name' => $this->faker->word() . '.jpg',
            'file_path' => 'uploads/' . $this->faker->word() . '.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => $this->faker->numberBetween(1000, 5000000),
            'disk' => 'public',
            'saved_at' => now(),
        ];
    }
}
