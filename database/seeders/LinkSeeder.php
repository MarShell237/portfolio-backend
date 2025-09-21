<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Link;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Link::factory()
            ->count(3)
            ->create()
            ->each(function ($link) {
                $link->file()->create(File::factory()->make()->toArray());
            });
    }
}
