<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LatestNewsController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});



Route::get('latest-news', [LatestNewsController::class, 'index']);
Route::get('latest-news/{latest_news}', [LatestNewsController::class, 'show']);

Route::middleware(['auth:sanctum', 'admin.check'])->group(function () {


    Route::post('/roles/assign', [RoleManagementController::class, 'assign']);
    Route::post('/roles/revoke', [RoleManagementController::class, 'revoke']);
    Route::post('/roles/update', [RoleManagementController::class, 'update']);

    Route::apiResource('courses', CourseController::class);

    Route::post('courses/assign-instructor', [CourseController::class, 'assign']);

    Route::delete('/courses/{courseId}/media/{mediaId}', [CourseController::class, 'destroyMedia']);

    Route::apiResource('latest-news', LatestNewsController::class)->except(['index', 'show']);
});

// Test
Route::middleware('auth:sanctum')->get('/user-profile', [RoleManagementController::class, 'checkMyRole']);



Route::middleware(['auth:sanctum', 'instructorOrAdmin.check'])->group(function () {

    Route::apiResource('lessons', LessonController::class)->except(['show']);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('lessons/{lesson}', [LessonController::class, 'show']);

    Route::apiResource('reviews', ReviewController::class);
});
