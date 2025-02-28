<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $users = $query->paginate(7);

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
        $user = User::with('country')->find($id);

        if ($user) {
            $responseData = [
                'id' => $user->id,
                'username' => $user->username,
                'country_id' => $user->country_id,
                'created_at' => $user->created_at,
                'country' => $user->country,
            ];

            if ($user->image) {
                $responseData['image'] = "/storage/" . $user->image;
            }

            return response()->json([
                'error' => false,
                'data' => [$responseData],
                'totalPages' => 1,
                'per_page' => 4,
                'to' => 4,
                'total' => 1
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'User not found',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:4',
            'country_id' => 'required|integer|exists:countries,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'country_id' => $request->country_id,
            'image' => $imagePath,
        ]);

        return response()->json($user, 201);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'country_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::findOrFail($id);
        $user->username = $validated['username'];
        $user->country_id = $validated['country_id'];

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $path = $request->file('image')->store('images', 'public');
            $user->image = $path;
        }

        $user->save();

        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'country_id' => $user->country_id,
            'created_at' => $user->created_at,
            'country' => $user->country,
            'image' => $user->image,
        ]);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
