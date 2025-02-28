<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::min(4)->letters()->numbers(), 'confirmed'],
            'country_id' => ['required', 'integer', 'exists:countries,id']
        ]);

        $data = $request->only(['username', 'password', 'country_id']);
        $data['password'] = bcrypt($data['password']);

        try {
            $user = User::query()->create($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User creation failed.'], 500);
        }

        return $this->success([
            'token' => $user->createToken('authToken')->accessToken
        ]);
    }

    protected function success(array $data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
