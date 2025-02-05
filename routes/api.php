<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProgressController;
use App\Http\Middleware\JwtMiddleware;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // route course
    Route::get('/courses', [CourseController::class, 'get_all_course']);
    Route::post('/course', [CourseController::class, 'create_course']);
    Route::get('/course/{id}', [CourseController::class, 'get_detail_course']);
    Route::patch('/course/{id}', [CourseController::class, 'update_course']);
    Route::delete('/course/{id}', [CourseController::class, 'delete_course']);
    
    // route progress
    Route::get('/progress', [ProgressController::class, 'get_all_progress']);
    Route::post('/progress-start', [ProgressController::class, 'start']);
    Route::patch('/progress-complete/{id}', [ProgressController::class, 'complete']);
});

