<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends Controller
{
    public function index(){
        return Tag::select('id')->paginate(10);
    }

    public function show(Tag $tag){
        return new TagResource($tag);
    }
}
