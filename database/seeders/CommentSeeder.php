<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visitors = User::role(UserRole::VISITOR->value)
                            ->orderBy('id')
                            ->get();
        $projects = Project::all();
        $posts = Post::all();

        foreach ($projects as $project) {
            for ($i = 0; $i < 10; $i++) {
                $visitor = $visitors->random();
                Comment::create([
                    'commenter_id' => $visitor->id,
                    'commentable_id' => $project->id,
                    'commentable_type' => (new Project())->getMorphClass(),
                    'content' => 'Commentaire ' . ($i + 1) . ' sur le projet ' . $project->id,
                ]);
            }
        }

        foreach ($posts as $post) {
            for ($i = 0; $i < 10; $i++) {
                $visitor = $visitors->random();
                Comment::create([
                    'commenter_id' => $visitor->id,
                    'commentable_id' => $post->id,
                    'commentable_type' => (new Post())->getMorphClass(),
                    'content' => 'Commentaire ' . ($i + 1) . ' sur le post ' . $post->id,
                ]);
            }
        }
    }
}
