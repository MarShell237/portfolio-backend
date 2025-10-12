<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Post;
use App\Models\Project;
use App\Models\Share;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShareSeeder extends Seeder
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

        foreach ($visitors as $visitor) {
            // Create shares for projects
            foreach ($projects as $project) {
                Share::create([
                    'sharer_id' => $visitor->id,
                    'shareable_id' => $project->id,
                    'shareable_type' => (new Project())->getMorphClass(),
                    'platform' => 'whatsapp',
                ]);
            }

            // Create shares for posts
            foreach ($posts as $post) {
                Share::create([
                    'sharer_id' => $visitor->id,
                    'shareable_id' => $post->id,
                    'shareable_type' => (new Post())->getMorphClass(),
                    'platform' => 'whatsapp',
                ]);
            }
        }
    }
}
