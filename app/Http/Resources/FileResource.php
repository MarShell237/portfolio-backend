<?php

namespace App\Http\Resources;

use App\Helpers\MorphRoute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'fileable'  => MorphRoute::make($this->fileable_type, $this->fileable_id),
            'file_name' => $this->file_name,
            'file_path' => Storage::disk($this->disk)->url($this->file_path),
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'disk'      => $this->disk,
            'saved_at'  => $this->saved_at->toDateTimeString(),
        ];
    }
}
