<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\File;

class FileController extends Controller
{
    public function show(File $file){
        return new FileResource($file); 
    } 
}
