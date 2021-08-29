<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                'message' => 'Invalid login details'
                        ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        //Issue token to user.
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        // Remove current active token.
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'deleted' => true,
        ]);
    }
}
