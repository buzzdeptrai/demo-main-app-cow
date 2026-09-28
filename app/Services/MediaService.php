<?php

namespace App\Services;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public function upload(HasMedia $model, UploadedFile $file, string $collection = 'default'): Media
    {
        return $model
            ->addMedia($file)
            ->toMediaCollection($collection);
    }

    public function uploadMultiple(HasMedia $model, array $files, string $collection = 'default'): array
    {
        $media = [];

        foreach ($files as $file) {
            $media[] = $this->upload($model, $file, $collection);
        }

        return $media;
    }

    public function delete(int $mediaId): bool
    {
        $media = Media::findOrFail($mediaId);
        $media->delete();

        return true;
    }

    public function getMediaForModel(HasMedia $model, string $collection = 'default')
    {
        return $model->getMedia($collection);
    }
}
