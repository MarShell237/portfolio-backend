<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()
            ->count(20)
            ->create()
            ->each(function ($project) {
                $project->file()->create(File::factory()->make()->toArray());
                $project->tags()->attach(Tag::factory()->count(3)->create());
            });
    }
}
