<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var User $visitor */
        $visitor = User::factory()->create();
        // $role = Role::firstOrCreate(['name' => UserRole::visitor->value,'guard_name' => 'api']);
        // $visitor->assignRole($role);
        return [
            'content' => $this->faker->sentence(),
            'commenter_id' => $visitor->id,
            'commentable_id' => Project::factory(),
            'commentable_type' => Project::class,
        ];
    }
}
