<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService {
    public function register(array $data) {
        return DB::transaction(function() use ($data) {
            $user = User::create([
               'name' => $data['name'],
               'email' => $data['email'],
               'password' => Hash::make($data['password']),
            ]);

            $workspace = $user->workspaces()->create([
                'name' => $data['name'] . "'s Workspace",
                'slug' => Str::slug($user->name . '-' . uniqid()),
            ],
            [
                'role' => 'owner',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        });
    }
    public function login(array $credentials) {
        if (!Auth::Attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();

        $token = $user->createToken('authToken')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
