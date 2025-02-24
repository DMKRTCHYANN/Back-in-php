<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'country_id' => 'nullable|integer|exists:countries,id'
        ]);

        $query = User::query()->with('country');

        if (!empty($request->get('country_id'))) {
            $query->where('country_id', $request->country_id);
        }

        $users = $query->paginate(4);

        return response()->json([
            'error' => false,
            'data' => UserResource::collection($users),
            'totalPages' => $users->lastPage(),
            'per_page' => $users->perPage(),
            'to' => $users->currentPage() * $users->perPage(),
            'total' => $users->total(),
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'country_id' => 'required|integer|exists:countries,id',
        ]);
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'country_id' => $request->country_id,
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'string|max:255',
            'password' => 'string|min:4|nullable',
            'country_id' => 'integer|exists:countries,id',
        ]);

        $data = $request->only(['username', 'country_id']);

        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'User updated successfully.']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
