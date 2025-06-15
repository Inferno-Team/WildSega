<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\tags\StoreTagRequest;
use App\Http\Requests\tags\UpdateTagRequest;

/**
 *  @group Tags
 * @authenticated
 * @middleware auth:sanctum
 * @middleware can:admin
 */

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index()
    {
        $tags = Tag::all();  // Retrieve all tags

        return response()->json([
            'tags' => TagResource::collection($tags),
            'message' => 'all tags retrived successfully.',
        ]);  // Return the tags as a collection of resources
    }

    /**
     * Create Tag (Admin Only)
     *
     * @bodyParam name string required The name of the tag. Example: "Rare"
     * @bodyParam description string A description of the tag. Example: "Rare plants"
     * @response 201 {
     *   "id": 1,
     *   "name": "Rare",
     *   "description": "Rare plants"
     * }
     * @response 403 {
     *   "message": "This action is unauthorized."
     * }
     */
    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->validated());
        return response()->json([
            'tag' => TagResource::make($tag),
            'message' => 'Tag created successfully.',
        ], 201);
    }
    /**
     * Update Tag (Admin Only)
     * @bodyParam name string required The name of the tag. Example: "Rare"
     * @response 201 {
     *   "id": 1,
     *   "name": "Rare",
     * }
     * @response 403 {
     *   "message": "This action is unauthorized."
     * }
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
        return response()->json([
            'tag' => TagResource::make($tag),
            'message' => 'Tag updated successfully.',
        ], 201);
    }

    /**
     * Delete Tag (Admin Only)
     * @response 204 {
     *   "message": "Tag deleted successfully."
     * }
     * @response 403 {
     *   "message": "This action is unauthorized."
     * }
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->noContent();
    }
}
