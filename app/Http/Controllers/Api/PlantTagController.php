<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlantDiscoveryResource;
use App\Http\Resources\TagResource;
use App\Models\Plant;


/**
 * @group PlantTagController
 */
class PlantTagController extends Controller
{
    /**
     * Display a listing of tags associated with the specified plant.
     */
    public function index(Plant $plant)
    {
        // Return the tags associated with the plant
        return TagResource::collection($plant->tags);
    }

    /**
     * Display a listing of discoveries associated with the specified plant.
     */
    public function discoveries(Plant $plant)
    {
        // Return the discoveries associated with the plant
        return PlantDiscoveryResource::collection($plant->discoveries);
    }
}
