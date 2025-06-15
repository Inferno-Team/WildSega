<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use App\Models\User;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserPreferenceResource;
use App\Http\Requests\user\preferences\StoreUserPreferenceRequest;
use App\Http\Requests\user\preferences\DestroyUserPreferenceRequest;

/**
 * @group User Preferences
 * @authenticated
 * @middleware auth:sanctum
 */
class UserPreferenceController extends Controller
{
    /**
     * Display the authenticated user's preferences.
     * 
     */
    public function index()
    {
        $user = auth()->user();
        $user = Auth::user();
        return UserPreferenceResource::collection($user->tags);  // Assuming the relation is defined on the User model
    }

    /**
     * Store the authenticated user's preference.
     */
    public function store(StoreUserPreferenceRequest $request)
    {
        $user = Auth::user();
        //$request->tag_id
        //$request->input('tag_id')
        // $data = $request->validated()['tag_id'];
        $tag = Tag::findOrFail($request->validated()['tag_id']);
        $user->tags()->attach($tag);
        return response()->json([
            'message' => 'tag attached to user successfully.',
            'tag' => TagResource::make($tag)
        ]);

        // $user = auth()->user();
        // $tag = Tag::findOrFail($request->validated()['tag_id']);

        // // Attach the tag to the user
        // $user->tags()->attach($tag);

        // return response()->json([
        //     'message' => 'Tag preference stored successfully.',
        //     'preference' => new TagResource($tag),
        // ]);
    }

    /**
     * Remove the authenticated user's preference.
     */
    public function destroy(Tag $tag)
    {
        $user = auth()->user();

        // Detach the tag from the user
        $user->tags()->detach($tag);

        return response()->json([
            'message' => 'Tag preference removed successfully.',
        ]);
    }
}
