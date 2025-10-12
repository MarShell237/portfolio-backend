<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends Controller
{
    public function index(){
        return ApiResponse::ok(
            'Tags retrieved successfully',
            Tag::select('id')->latest('id')->paginate(10)->toArray(),
        );
    }

    public function show(Tag $tag){
        return ApiResponse::ok(
            'Tag details retrieved successfully',
            [
                'data' => new TagResource($tag),
            ]
        );
    }
}
