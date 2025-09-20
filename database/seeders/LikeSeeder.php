<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Project;
use App\Models\Visitor;
use App\Models\Like;
use App\Models\User;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visitors = User::role(UserRole::VISITOR->value)
                            ->orderBy('id')
                            ->get();

        // For Posts
        foreach (Post::all() as $post) {
            $likeCount = rand(1, min(10, $visitors->count()));
            $randomVisitors = $visitors->random($likeCount);
            foreach ($randomVisitors as $visitor) {
                Like::create([
                    'likeable_id' => $post->id,
                    'likeable_type' => Post::class,
                    'liker_id' => $visitor->id,
                ]);
            }
        }

        // For Projects
        foreach (Project::all() as $project) {
            $likeCount = rand(1, min(10, $visitors->count()));
            $randomVisitors = $visitors->random($likeCount);
            foreach ($randomVisitors as $visitor) {
                Like::create([
                    'likeable_id' => $project->id,
                    'likeable_type' => Project::class,
                    'liker_id' => $visitor->id,
                ]);
            }
        }
    }
}
