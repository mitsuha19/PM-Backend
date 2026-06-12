<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckWorkSpaceAccess;
use App\Http\Controllers\ProjectController;
use \App\Http\Controllers\TaskController;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Welcome to the API'
    ]);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('workspace', WorkspaceController::class);

    Route::middleware([CheckWorkSpaceAccess::class])->group(function () {
        Route::apiResource('project', ProjectController::class);
        Route::apiResource('projects.tasks', TaskController::class);
    });

});

