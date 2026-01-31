<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;


class Lesson extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'content',
        'course_id',
        'order',
    ];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('lesson_files');
    }
}
