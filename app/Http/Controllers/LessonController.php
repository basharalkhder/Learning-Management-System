<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Services\LessonService;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use FFI\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LessonController extends Controller
{
    protected $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    public function index()
    {
        try {
            $lessons = $this->lessonService->getAllLessons();
            return response_success(LessonResource::collection($lessons), 200, 'All Lessons');
        } catch (Exception $e) {
            return response_error(null, $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request)
    {
        try {
            $lesson = $this->lessonService->createLesson($request->validated());
            return response_success(new LessonResource($lesson), 201, 'Lesson created successfully.');
        } catch (\Exception $e) {
            $code = (is_int($e->getCode()) && $e->getCode() >= 100 && $e->getCode() < 600) ? $e->getCode() : 500;
            return response_error(null, $code, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($lessonId)
    {
        try {

            $lesson = $this->lessonService->getLessonForUser($lessonId);

            $lesson->load(['course', 'media']);

            return response_success(
                new LessonResource($lesson),
                200,
                'Lesson retrieved successfully.'
            );
        } catch (ModelNotFoundException $e) {

            return response_error(null, 404, $e->getMessage());
        } catch (\Exception $e) {
            $code = ($e->getCode() == 403) ? 403 : 500;
            return response_error(null, $code, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, $lessonId)
    {
        $data = $request->validated();
        try {
            $updatedLesson = $this->lessonService->updateLesson($data, $lessonId);
            return response_success(new LessonResource($updatedLesson), 200, 'Lesson updated successfully');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, $e->getMessage());
        } catch (\Exception $e) {
            return response_error(null, 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($lessonId)
    {
        try {

            $this->lessonService->deleteLesson($lessonId);

            return response_success(null, 200, 'Lesson deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, 'Lesson not found.');
        } catch (\Exception $e) {
            $code = ($e->getCode() == 403) ? 403 : 500;
            return response_error(null, $code, $e->getMessage());
        }
    }
}
