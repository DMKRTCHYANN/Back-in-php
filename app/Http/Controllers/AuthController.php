<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::query()
            ->where('username', $request->get('username'))
            ->first();

        if (is_null($user) || !Hash::check($request->get('password'), $user->password)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'access_token' => $accessToken
        ]);
    }
}
