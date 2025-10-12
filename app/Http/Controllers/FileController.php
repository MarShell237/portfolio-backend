<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\FileResource;
use App\Models\File;

class FileController extends Controller
{
    public function show(File $file){
        return ApiResponse::ok(
            'File retrieved successfully`',
            [
                'data' => new FileResource($file),
            ]
        );
    } 
}
