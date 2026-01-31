<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'content'    => $this->content,
            'order'      => $this->order,

            'course' => [
                'id'    => $this->course_id,
                'name'  => $this->whenLoaded('course', fn() => $this->course->title),
            ],

            // بما أنك ستستخدم Spatie Media Library لاحقاً، يمكنك إضافة هذا الحقل
            'media'      => $this->when($this->relationLoaded('media'), function () {
                return $this->getMedia('lesson_files')->map(fn($file) => [
                    'name' => $file->name,
                    'url'  => $file->getFullUrl(),
                    'size' => $file->human_readable_size,
                ]);
            }),

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
