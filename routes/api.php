<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\AuthController;

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

    

});

// Test
Route::middleware('auth:sanctum')->get('/user-profile', [RoleManagementController::class, 'checkMyRole']);