<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CourseService
{
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

            $courses = $query->with('instructor')->latest()->paginate(10);


            if ($courses->isEmpty() && !empty($filters['instructor_id'])) {
                throw new \Exception("This instructor currently has no courses assigned.", 404);
            }

            return $courses;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function createCourse(array $data)
    {
        try {
            return Course::create($data);
        } catch (Exception $e) {
            throw new Exception("Error while creating course: " . $e->getMessage());
        }
    }

    public function updateCourse(array $data, $id)
    {
        $course = $this->getCourseById($id);
        $course->update($data);
        return $course;
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
        }
        catch (Exception $e) {
           
            throw new Exception("An error occurred: " . $e->getMessage(), 500);
        }
    }
}
