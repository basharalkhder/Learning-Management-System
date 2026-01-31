<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::middleware(['auth:sanctum', 'admin.check'])->group(function () {

    Route::post('/roles/assign', [RoleManagementController::class, 'assign']);
    Route::post('/roles/revoke', [RoleManagementController::class, 'revoke']);
    Route::post('/roles/update', [RoleManagementController::class, 'update']);

    Route::apiResource('courses', CourseController::class);

    Route::post('courses/assign-instructor', [CourseController::class, 'assign']);

    Route::delete('/courses/{courseId}/media/{mediaId}', [CourseController::class, 'destroyMedia']);
});

// Test
Route::middleware('auth:sanctum')->get('/user-profile', [RoleManagementController::class, 'checkMyRole']);



Route::middleware(['auth:sanctum', 'instructorOrAdmin.check'])->group(function () {

    Route::apiResource('lessons', LessonController::class)->except(['show']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('lessons/{lesson}', [LessonController::class, 'show']);
});
