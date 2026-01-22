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
        'capacity'
    ];



    public function registerMediaCollections(): void
    {
        // مجموعة للصور - تحذف القديم إذا أردت صورة واحدة فقط (حسب منطق مشروعك)
        $this->addMediaCollection('course_images');

        // مجموعة لملفات الـ PDF
        $this->addMediaCollection('course_pdfs');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
