<?php

use App\Http\Controllers\AuthController;
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

    Route::middleware([CheckWorkSpaceAccess::class])->group(function () {

    });

});

