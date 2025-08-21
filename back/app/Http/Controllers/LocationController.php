<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller{
    function store(Request $request){
        $request->validate([
            'date_time_phone' => 'required|date',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'user_id' => 'required|exists:users,id'
        ]);

        $location = new Location();
        $location->date_time = now();
        $location->date_time_phone = $request->date_time_phone;
        $location->lat = $request->lat;
        $location->lng = $request->lng;
        $location->user_id = $request->user_id;
        $location->save();

        return response()->json(['message' => 'Location stored successfully'], 201);
    }
}
