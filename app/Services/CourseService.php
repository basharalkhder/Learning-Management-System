<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CourseService
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function getCourseById($id)
    {
        try {
            return Course::with('instructor')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("The course with ID {$id} was not found.", 404);
        } catch (\Exception $e) {
            throw new \Exception("An error occurred: " . $e->getMessage(), 500);
        }
    }

    public function getAllCourses(array $filters)
    {
        try {
            $query = Course::query();


            if (!empty($filters['instructor_id'])) {
                $instructorId = $filters['instructor_id'];


                $instructor = User::find($instructorId);

                if (!$instructor) {
                    throw new \Exception("The provided instructor ID does not exist.", 404);
                }

                if (!$instructor->hasRole('Instructor')) {
                    throw new \Exception("The selected user is not authorized as an instructor.", 403);
                }

                $query->where('instructor_id', $instructorId);
            }
            if (!empty($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            }
            if (!empty($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }
            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }

            $courses = $query->with('instructor')->latest()->paginate(10);



            return $courses;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function createCourse(array $data)
    {
        try {
            $course = Course::create($data);

            if (!empty($data['images'])) {
                $this->mediaService->uploadMultipleMedia($course, $data['images'], 'course_images');
            }

            if (!empty($data['pdfs'])) {
                $this->mediaService->uploadMultipleMedia($course, $data['pdfs'], 'course_pdfs');
            }

            return $course->load('media');
        } catch (Exception $e) {
            throw new Exception("Error while creating course: " . $e->getMessage());
        }
    }

    public function updateCourse(array $data, $id)
    {

        $course = $this->getCourseById($id);
        $course->update($data);

        
        if (isset($data['images']) && is_array($data['images'])) {
            $this->mediaService->uploadMultipleMedia($course, $data['images'], 'course_images');
        }

        
        if (isset($data['pdfs']) && is_array($data['pdfs'])) {
            $this->mediaService->uploadMultipleMedia($course, $data['pdfs'], 'course_pdfs');
        }

        return $course->load('media');
    }

    public function deleteCourse($id)
    {
        $course = $this->getCourseById($id);
        return $course->delete();
    }


    public function assignInstructor(array $data)
    {
        $courseId = $data['course_id'];
        $instructorId = $data['instructor_id'];

        try {
            $course = $this->getCourseById($courseId);
            $user = User::findOrFail($instructorId);
            if (!$user) {
                throw new ModelNotFoundException("Instructor not found.", 404);
            }


            if (!$user->hasRole('Instructor')) {
                throw new Exception("The selected user is not a valid Instructor.");
            }

            $course->update(['instructor_id' => $instructorId]);

            return $course->load('instructor');
        } catch (Exception $e) {

            throw new Exception("An error occurred: " . $e->getMessage(), 500);
        }
    }
}
