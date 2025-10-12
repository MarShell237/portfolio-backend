<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    public function index(){
        return ApiResponse::ok(
            'Posts retrieved successfully',
            Post::select('id')->latest('id')->paginate(10)->toArray(),
        );
    }

    public function show(Post $post){
        return ApiResponse::ok(
            'Post details retrieved successfully',
            [
                'data' => new PostResource($post),
            ]
        );
    }
}
