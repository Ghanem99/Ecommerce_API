<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index() 
    {
        $locations = Location::all();
        return response()->json($locations);
    }
    
    public function store(Request $request) 
    {
        $request->validate([
            'street' => 'required|string',
            'building' => 'required|string',
            'area' => 'required|string',
        ]);
        dd(Auth::id());

        $location = Location::create([
            'street' => $request->street,
            'building' => $request->building,
            'area' => $request->area,
            'user_id' => Auth::id(),
        ]);
        dd($location);
        return response()->json($location, 201);
    }

    public function show(Location $location) 
    {
        return response()->json($location);
    }

    public function update(Request $request, Location $location) 
    {
        $request->validate([
            'street' => 'required|string',
            'building' => 'required|string',
            'area' => 'required|string',
        ]);
        
        $location->update([
            'street' => $request->street,
            'building' => $request->building,
            'area' => $request->area,
        ]);
        
        return response()->json($location);
    }

    public function destroy(Location $location) 
    {
        $location->delete();
        return response()->json(null, 204);
    }
}
