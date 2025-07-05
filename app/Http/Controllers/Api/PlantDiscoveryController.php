<?php

namespace App\Http\Controllers\API;

use App\Jobs\AskPlantAIJob;
use App\Models\PlantDiscovery;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PlantDiscoveryRequest;
use App\Http\Resources\PlantDiscoveryResource;
use App\Jobs\FindPlantByImageJob;
use App\Models\Plant;
use Illuminate\Support\Facades\Http;
use Log;

/**
 * @group Discoveries
 * @authenticated
 * @middleware auth:sanctum
 */
class PlantDiscoveryController extends Controller
{

    /**
     * Display a listing of plant discoveries.
     */
    public function index()
    {
        return  PlantDiscoveryResource::collection(PlantDiscovery::all());
    }

    /**
     * Store a newly created plant discovery.
     */
    public function store(PlantDiscoveryRequest $request)
    {
        $data = [...$request->validated(), 'user_id' => Auth::id()];

        $discovery = PlantDiscovery::create($data);
        $discovery->addMedia($request->file('image'))->toMediaCollection('images');
        $imagePath = $discovery->media->first()->getPath();
        dispatch(new FindPlantByImageJob($imagePath, $discovery->id));
        return new PlantDiscoveryResource($discovery);
    }

    /**
     * Display the specified plant discovery.
     */
    public function show(PlantDiscovery $discovery)
    {
        return new PlantDiscoveryResource($discovery);  // Return a single discovery as a resource
    }

    /**
     * Update the specified plant discovery.
     */
    public function update(PlantDiscoveryRequest $request, PlantDiscovery $discovery)
    {
        $data = $request->validated();

        $discovery->update($data);
        $discovery->addMedia($request->file('image'))->toMediaCollection('images');

        return new PlantDiscoveryResource($discovery);  // Return updated discovery as a resource
    }

    /**
     * Remove the specified plant discovery.
     */
    public function destroy(PlantDiscovery $discovery)
    {
        $discovery->delete();

        return response()->noContent();  // Return no content on successful deletion
    }
}
