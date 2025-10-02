<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

trait HasFile
{
    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    /**
     * Get the ID of the associated file, if it exists.
     *
     * @return int|null
     */
    public function getFileId(): ?int
    {
        return File::where('fileable_id', $this->id)
            ->where('fileable_type', get_class($this))
            ->value('id');
    }

    public function getFileUrl(): string{
        return $this->file ? Storage::disk($this->file->disk)->url($this->file->file_path) : null;
    }

    public function setFile(
        UploadedFile $uploadedFile,
        string $path,
        bool $hasName  = false,
        string $disk = 'public',
    ): File {
        if ($this->file) {
            Storage::disk($this->file->disk)->delete($this->file->file_path);
            $this->file->delete();
        }

        $originalName = $uploadedFile->getClientOriginalName();

        // Prevent overwriting an existing file in the same folder/version.
        // This typically means the file is already part of another media resource,
        // and creating a duplicate here would be redundant or conflict with version control.
        if ($hasName) {
            $fullPath = $path . '/' . $originalName;
            if (Storage::disk($disk)->exists($fullPath)) {
                abort(Response::HTTP_BAD_REQUEST, "A file named '{$originalName}' already exists in this folder.");
            }

            $storedPath = $uploadedFile->storeAs($path, $originalName, $disk);
        } else {
            $storedPath = $uploadedFile->store($path, $disk);
        }

        return $this->file()->create([
            'file_name' => $originalName,
            'file_path' => $storedPath,
            'file_type' => $uploadedFile->getClientMimeType(),
            'file_size' => $uploadedFile->getSize(),
            'disk' => $disk,
        ]);
    }
}
