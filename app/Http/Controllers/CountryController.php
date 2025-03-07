<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return response()->json([
            'error' => false,
            'data' => CountryResource::collection($countries),
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $country = Country::create($request->all());

        return response()->json([
            'error' => false,
            'data' => new CountryResource($country),
        ], 201);
    }

    public function show($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'error' => true,
                'message' => 'Country not found',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'data' => new CountryResource($country),
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'error' => true,
                'message' => 'Country not found',
            ], 404);
        }

        $request->validate(['name' => 'required|string|max:255']);

        $country->update($request->all());

        return response()->json([
            'error' => false,
            'data' => new CountryResource($country),
        ], 200);
    }

    public function destroy($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'error' => true,
                'message' => 'Country not found',
            ], 404);
        }

        $country->delete();

        return response()->json([
            'error' => false,
            'message' => 'Country deleted successfully',
        ], 200);
    }
}
