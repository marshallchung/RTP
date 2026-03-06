<?php

namespace App\Http\Controllers;

use App\SignLocation;

class SignLocationController extends Controller
{
    public function index()
    {
        $signLocations = [];
        SignLocation::with('user', 'files')->get()->each(function (SignLocation $signLocation) use (&$signLocations) {
            $signLocations[] = [
                'latitude'  => $signLocation->latitude,
                'longitude' => $signLocation->longitude,
                'info'      => $signLocation->info,
            ];
        });

        return view('sign-location.index', compact('signLocations'));
    }
}
