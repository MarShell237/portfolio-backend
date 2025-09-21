<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        return Category::select('id')->paginate(10);
    }

    public function show(Category $category){
        return new CategoryResource($category->load('file:id'));
    }
}
