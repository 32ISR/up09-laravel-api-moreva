<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            "email" => "email|unique:users|required",
            "name" => "string|required|max:255",
            "password" => "string|required|min:8|confirmed"
        ]);

        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "access_token" => $token, 
            "user" => $user,
            "token_type" => "Bearer"
        ], 201);
    }

    public function login(Request $request) {

        $data = $request->validate([
            "email" => "email|required",
            "password" => "string|required"
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
            "message" => "Пароль или почта неправильная"
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "access_token" => $token, 
            "user" => $user,
            "token_type" => "Bearer"
        ], 200);

    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return responce()->json(['message' => 'Сессия успешно уничтожена'], 200);
    }
}
