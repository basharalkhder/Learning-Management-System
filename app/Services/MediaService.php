<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{

    public function uploadMultipleMedia(HasMedia $model, array $files, string $collectionName)
    {
        foreach ($files as $file) {
            $model->addMedia($file)->toMediaCollection($collectionName);
        }
    }

    
    public function deleteMediaFromCourse($courseId, int $mediaId)
    {

        $course = Course::find($courseId);

        if (!$course) {
            throw new ModelNotFoundException("Course not found");
        }


        $media = Media::find($mediaId);

        if (!$media) {
            throw new ModelNotFoundException("The media file with ID $mediaId does not exist.");
        }

        if ((int)$media->model_id !== (int)$courseId || $media->model_type !== Course::class) {
            throw new \Exception(" This media does not belong to the specified course.");
        }

        return $media->delete();
    }
}
