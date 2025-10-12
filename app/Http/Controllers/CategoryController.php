<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        return ApiResponse::ok(
            'Categories retrieved successfully',
            Category::select('id')->latest('id')->paginate(10)->toArray(),
        );
    }

    public function show(Category $category){
        return ApiResponse::ok(
            'Category retrieved successfully',
            [
                'data' => new CategoryResource($category),
            ]
        );
    }
}
