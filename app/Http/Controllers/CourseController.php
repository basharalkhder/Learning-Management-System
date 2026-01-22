<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;
use App\Services\CourseService;
use App\Services\MediaService;
use App\Http\Resources\CourseResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Requests\AssignInstructorRequest;


class CourseController extends Controller
{
    protected $courseService;
    protected $mediaService;

    public function __construct(CourseService $courseService, MediaService $mediaService)
    {
        $this->courseService = $courseService;
        $this->mediaService = $mediaService;
    }



    public function index(Request $request)
    {
        try {
            $courses = $this->courseService->getAllCourses($request->all());

            return response_success(CourseResource::collection($courses), 200, 'Courses retrieved successfully');
        } catch (\Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() <= 599) ? $e->getCode() : 500;
            return response_error(null, $statusCode, $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();
        try {

            $course = $this->courseService->createCourse($data);
            return response_success(new CourseResource($course), 201, 'Course created successfully');
        } catch (\Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $course = $this->courseService->getCourseById($id);

            return response_success(new CourseResource($course), 200, 'Course details retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, $e->getMessage());
        } catch (\Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $course = $this->courseService->updateCourse($data, $id);
            return response_success(new CourseResource($course), 200, 'Course updated successfully');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, $e->getMessage());
        } catch (\Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->courseService->deleteCourse($id);
            return response_success(null, 200, 'Course deleted successfully');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, $e->getMessage());
        } catch (\Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }

    public function assign(AssignInstructorRequest $request)
    {
        $data = $request->validated();

        try {
            $course = $this->courseService->assignInstructor($data);
            return response_success(new CourseResource($course), 200, 'Instructor assigned successfully');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, $e->getMessage());
        } catch (\Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }

    public function destroyMedia($courseId, $mediaId)
    {
        try {
           
            $this->mediaService->deleteMediaFromCourse($courseId, $mediaId);

            return response_success(null, 200, 'Media deleted successfully from the course.');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, $e->getMessage());
        } catch (\Exception $e) {
            return response_error(null, 400, $e->getMessage());
        }
    }
}
