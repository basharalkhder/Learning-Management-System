<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UserResource;
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

        list($user, $token) = $this->authService->register($data);

        $responseData = [
            'user'         => new UserResource($user),
            'access_token' => $token,
        ];

        return response_success($responseData, 201, 'User registered successfully');
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {

            return response_error(null, 401, 'Invalid email or password credentials.');
        }

        list($user, $token) = $result;

        $responseData = [
            'user'         => new UserResource($user),
            'access_token' => $token,
        ];


        return response_success($responseData, 200, 'Login successful.');
    }

    public function logout()
    {
        
        auth()->user()->currentAccessToken()->delete();

        return response_success(null, 200, 'Logged out successfully and token deleted.');
    }
}
