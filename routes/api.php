<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\CheckWorkSpaceAccess;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Welcome to the API'
    ]);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function(Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('workspace', WorkspaceController::class);

    Route::middleware([CheckWorkSpaceAccess::class])->group(function () {

    });

});

