<?php

namespace App\Services;

use App\Enums\EnrollmentStatus;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LessonService
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function find_lesson_by_id($lessonId)
    {
        try {
            return Lesson::findOrFail($lessonId);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("The lesson with ID {$lessonId} was not found.", 404);
        } catch (\Exception $e) {
            throw new \Exception("An error occurred: " . $e->getMessage(), 500);
        }
    }

    public function getAllLessons()
    {
        try {

            $user = Auth::user();

            if ($user->hasRole('Admin')) {
                return Lesson::with('course', 'media')->orderBy('order', 'asc')->get();
            }

            return Lesson::whereIn('course_id', $user->instructorCourses()->pluck('id'))
                ->with('course', 'media')
                ->orderBy('order', 'asc')
                ->get();
        } catch (\Exception $e) {
            throw new \Exception("An error occurred: " . $e->getMessage(), 500);
        }
    }

    public function getLessonForUser(int $lessonId)
    {
        $lesson = $this->find_lesson_by_id($lessonId)->load('course');
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            return $lesson;
        }

        if ($user->hasRole('Instructor')) {
            if ($lesson->course->instructor_id === $user->id) {
                return $lesson;
            }
            throw new \Exception("Unauthorized: You are not the instructor of this course.", 403);
        }


        $enrollment = $user->enrolledCourses()
            ->where('course_id', $lesson->course_id)
            ->first();

        if (!$enrollment || $enrollment->pivot->status !==EnrollmentStatus::ACTIVE->value) {
            throw new \Exception("Access Denied: You must be an active student in this course to view the lesson.", 403);
        }

        return $lesson;
    }


    public function createLesson(array $data)
    {
        $user = Auth::user();


        if ($user->hasRole('Instructor') && !$user->hasRole('Admin')) {
            $this->verifyCourseOwnership($user->id, $data['course_id']);
        }

        $lesson = Lesson::create($data);

        if (isset($data['files']) && is_array($data['files'])) {
            $this->mediaService->uploadMultipleMedia($lesson, $data['files'], 'lesson_files');
        }
        return $lesson->load(['course', 'media']);
    }


    public function updateLesson(array $data, $lessonId)
    {
        $lesson = $this->find_lesson_by_id($lessonId);
        $user = Auth::user();

        if ($user->hasRole('Instructor') && !$user->hasRole('Admin')) {
            $this->verifyCourseOwnership($user->id, $lesson->course_id);

            if (isset($data['course_id']) && $data['course_id'] != $lesson->course_id) {
                $this->verifyCourseOwnership($user->id, $data['course_id']);
            }
        }

        $lesson->update($data);

        if (isset($data['files']) && is_array($data['files'])) {
            $this->mediaService->uploadMultipleMedia($lesson, $data['files'], 'lesson_files');
        }
        return $lesson->load(['course', 'media']);
    }


    public function deleteLesson($lessonId)
    {
        $lesson = $this->find_lesson_by_id($lessonId);
        $user = Auth::user();

        if ($user->hasRole('Instructor') && !$user->hasRole('Admin')) {
            $this->verifyCourseOwnership($user->id, $lesson->course_id);
        }

        return $lesson->delete();
    }


    private function verifyCourseOwnership($userId, $courseId)
    {
        $user = Auth::user();

        $ownsCourse = $user->instructorCourses()->where('id', $courseId)->exists();

        if (!$ownsCourse) {
            throw new Exception("Unauthorized: This course does not belong to you.", 403);
        }
    }
}
