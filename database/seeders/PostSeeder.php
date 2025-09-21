<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::factory()
            ->count(35)
            ->create()
            ->each(function ($post) {
                $post->file()->create(File::factory()->make()->toArray());
                $post->tags()->attach(Tag::factory()->count(3)->create());
            });
    }
}
