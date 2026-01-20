<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Auth\UserResource;

class CourseResource extends JsonResource
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
            'description' => $this->description,
            'price' => number_format((float) $this->price, 2) . ' SYP',
            'created_at' => $this->created_at->format('Y-m-d H:i'), 
            
           
            'instructor' => new UserResource($this->whenLoaded('instructor')),
        ];
    }
}
