<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreSpotRequest;
use App\Http\Requests\UpdateSpotRequest;
use App\Models\Spot;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the page query parameter
        $page = $request->query('page', 1);

        // Eager load the 'user' relationship and sort by latest created_at
        $spots = Spot::with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $page);
        
        // Return the spots along with the related user data
        return response()->json($spots);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpotRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spot $spot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpotRequest $request, Spot $spot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        //
    }
}
