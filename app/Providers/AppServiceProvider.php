<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Link;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Relation::enforceMorphMap([
            'user' => User::class,
            'post' => Post::class,
            'project' => Project::class,
            'comment' => Comment::class,
            'tag' => Tag::class,
            'link' => Link::class,
        ]);
    }
}
