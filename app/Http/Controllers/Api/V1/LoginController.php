<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LoginController
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()->where('email', $request->validated('email'))->first();

        if (is_null($user)) {
            return response()->json([
                'message' => 'Invalid credentials.',
                'code' => 'BAD_LOGIN',
            ], 400);
        }

        $token = $user->createToken($request->validated('device_name'))->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token,
            ],
        ], 201);
    }
}
