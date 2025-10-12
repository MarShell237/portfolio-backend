<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\LinkResource;
use App\Models\Link;

class LinkController extends Controller
{
    public function index(){
        return ApiResponse::ok(
            'Links retrieved successfully',
            [
                'data' => Link::select('id')->paginate(10),
            ]
        );
    }

    public function show(Link $link){
        return ApiResponse::ok(
            'Link details retrieved successfully',
            [
                'data' => new LinkResource($link),
            ]
        );
    }
}
