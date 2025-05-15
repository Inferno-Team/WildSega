<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Notifications\DatabaseNotification;

/**
 * @group Notifications
 * @authenticated
 * @middleware auth:sanctum
 **/
class NotificationController extends Controller
{
    // Get the current user's notifications
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(10); // Paginate for better performance

        return response()->json([
            'notifications' =>  NotificationResource::collection($notifications),
            'unread_count' => $user->unreadNotifications->count(),
        ]);
    }

    // Mark a single notification as read
    public function markAsRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read.',
            'unread_count' => auth()->user()->unreadNotifications->count(),
        ]);
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read.',
            'unread_count' => 0,
        ]);
    }
}
