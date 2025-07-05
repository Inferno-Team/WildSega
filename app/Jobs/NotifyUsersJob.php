<?php

namespace App\Jobs;

use Log;
use App\Models\User;
use App\Models\PlantDiscovery;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NerbyDiscoverNotification;

class NotifyUsersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly PlantDiscovery $discovery)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tagsId = $this->discovery->plant->tags->pluck('id');
        $users = User::whereHas('preferences', function ($query) use ($tagsId) {
            $query->whereIn('tag_id', $tagsId);
        })->get()->filter(function (User $user) {

            $dLat = deg2rad($user->latitude - $this->discovery->latitude);
            $dLong = deg2rad($user->longitude - $this->discovery->longitude);
            $R = 6371; // eart rad in KM

            //a= sin²(Δφ/2) + cos φ1 ⋅ cos φ2 ⋅ sin²(Δλ/2)
            $a = pow(sin(($dLat) / 2), 2) +
                cos($user->latitude) *
                cos($this->discovery->latitude) *
                pow(sin($dLong), 2);
            //c = 2 ⋅ atan2( √a, √(1−a) )
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            //d = C . R
            $distance = $c * $R;
            Log::info("user " . $user->id . " have dis of " . $distance .
                ' for this plant : ' . $this->discovery->plant->name . ' , discovery id : '  . $this->discovery->id);
            return $user->notification_range_km >= $distance;
        });
        Notification::send($users, new NerbyDiscoverNotification($this->discovery));
    }
}
