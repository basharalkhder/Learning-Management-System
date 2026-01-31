<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'instructor_id',
        'price',
        'type',
        'capacity',
        'registration_deadline',
        'is_active'
    ];

    protected $casts = [
        'registration_deadline' => 'datetime',
        'is_active' => 'boolean',
    ];


    public function registerMediaCollections(): void
    {

        $this->addMediaCollection('course_images');


        $this->addMediaCollection('course_pdfs');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order', 'asc');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot('status', 'enrolled_at')
            ->withTimestamps();
    }


    protected static function booted()
    {
        static::retrieved(function ($course) {

            if ($course->is_active && $course->registration_deadline && $course->registration_deadline->isPast()) {


                $course->is_active = false;

                $course->save();
            }
        });
    }
}
