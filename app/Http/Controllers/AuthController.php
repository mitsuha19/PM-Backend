<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Service\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request) {
        $data = $request->validated();
        $result = $this->authService->register($data);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $result,
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
        ]);
    }

    public function login(LoginRequest $request) {
        $credential  = $request->validated();
        $result = $this->authService->login($credential);

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => $result,
            'access_token' => $result,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ]);
    }
}
