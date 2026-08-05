<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        ['user' => $user, 'token' => $token] = $this->service->register($request->validated());

        return response()->json([
            'data' => [
                'user' => new UserResource($user->load('umkm')),
                'token' => $token,
            ],
            'message' => 'Registrasi berhasil. Selamat datang di Kotakin!',
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        ['user' => $user, 'token' => $token] = $this->service->login($request->validated());

        return response()->json([
            'data' => [
                'user' => new UserResource($user->load('umkm')),
                'token' => $token,
            ],
            'message' => 'Login berhasil.',
        ], 200);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($request->user()->load('umkm')),
            'message' => 'Berhasil',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->service->logout($request->user());

        return response()->json([
            'data' => null,
            'message' => 'Logout berhasil.',
        ]);
    }
}
