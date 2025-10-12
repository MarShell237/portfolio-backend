<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\ProjectResource;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(){
        return ApiResponse::ok(
            'Projects retrieved successfully',
            Project::select('id')->latest('id')->paginate(10)->toArray(),
        );
    }

    public function show(Project $project){
        return ApiResponse::ok(
            'Project details retrieved successfully',
            [
                'data' => new ProjectResource($project->loadCount(['likes', 'comments', 'shares'])),
            ]
        );
    }
}
