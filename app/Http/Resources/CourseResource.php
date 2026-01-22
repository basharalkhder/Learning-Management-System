<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Auth\UserResource;
use Carbon\Carbon;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isPastDeadline = $this->registration_deadline && Carbon::parse($this->registration_deadline)->isPast();


        $status = ((bool)$this->is_active && !$isPastDeadline) ? 'Active' : 'Closed';
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'type'       => $this->type,
            'status'      => $status, 
            'registration_deadline' => $this->registration_deadline ? Carbon::parse($this->registration_deadline)->format('Y-m-d H:i') : null,
            'capacity' => $this->type === 'online' ? 'Unlimited' : $this->capacity,
            'description' => $this->description,
            'price' => number_format((float) $this->price, 2) . ' SYP',
            'created_at' => $this->created_at->format('Y-m-d H:i'),

            'images' => $this->getMedia('course_images')->map(function ($media) {
                return [
                    'id'   => $media->id,
                    'url'  => $media->getFullUrl(),
                    'name' => $media->file_name,
                ];
            }),

            'files' => $this->getMedia('course_pdfs')->map(function ($media) {
                return [
                    'id'   => $media->id,
                    'url'  => $media->getFullUrl(),
                    'name' => $media->file_name,
                    'size' => $media->human_readable_size,
                ];
            }),


            'instructor' => new UserResource($this->whenLoaded('instructor')),
        ];
    }
}
