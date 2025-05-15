<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlantResource;
use App\Models\Plant;

/**
 * @group Plants
 * @authenticated
 * @middleware auth:sanctum
 */


class PlantController extends Controller
{
    /**
     * Display a listing of plants.
     */
    public function index()
    {
        $plants = Plant::all();  // Retrieve all plants

        return PlantResource::collection($plants);  // Return the plants as a collection of resources
    }

    /**
     * Display the specified plant.
     */
    public function show(Plant $plant)
    {
        return new PlantResource($plant);  // Return the specific plant as a resource
    }
}
