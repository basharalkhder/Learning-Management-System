<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // الحقول التي يمكن تعبئتها عبر الـ Request
    protected $fillable = [
        'user_id',
        'course_id',
        'rating',
        'comment'
    ];

    /**
     * العلاقة مع المستخدم (الطالب الذي كتب المراجعة)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع الكورس (الكورس الذي تم تقييمه)
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
