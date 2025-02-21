<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        return response()->json(Country::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $country = Country::create($request->all());

        return response()->json(['message' => 'Country created successfully', 'country' => $country], 201);
    }

    public function show($id)
    {
        $country = Country::findOrFail($id);
        return response()->json(['country' => $country]);
    }


    public function update(Request $request, $id)
    {
        $country = Country::find($id);


        $request->validate(['name' => 'required|string|max:255']);

        $country->update($request->all());

        return response()->json(['message' => 'Country updated successfully', 'country' => $country]);
    }

    public function destroy($id)
    {
        $country = Country::find($id);
        if (!$country) {
            return response()->json(['message' => 'Country not found'], 404);
        }
        $country->delete();
            return response()->json(['message' => 'Country deleted successfully'], 200);
    }

}
