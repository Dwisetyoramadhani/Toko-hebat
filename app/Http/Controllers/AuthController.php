<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $result = $this->authService->register($data);

        return response()->json([
            'message' => 'Register success',
            'user' => $result['user'],
            'token' => $result['token']
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $result = $this->authService->login($data);

        if (!$result) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Login success',
            'user' => $result['user'],
            'token' => $result['token']
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Logout success'
        ]);
    }
}
