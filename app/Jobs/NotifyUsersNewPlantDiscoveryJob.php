<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Plant;
use App\Models\PlantDiscovery;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NearbyPlantDiscovered;
use Illuminate\Support\Facades\Notification;

class NotifyUsersNewPlantDiscoveryJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly PlantDiscovery $discovery,
        private readonly Plant $plant,
        private readonly array $tagsId

    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::whereHas('tags', function ($query) {
            $query->whereIn('id', $this->tagsId);
        })->get()->filter(function (User $user) {
            // Calculate distance between user and discovery (in km)
            $distance = calculateHaversineDistance(
                $user->latitude,
                $user->longitude,
                $this->discovery->latitude,
                $this->discovery->longitude
            );
            Log::info("distance between user " . $user->id .
                ' , ' . $this->discovery->plant->scientific_name
                . ' is = ' . $distance .
                ' , and user notification_range_km is : ' . $user->notification_range_km);

            // Check if within the user's notification range
            return $distance <= $user->notification_range_km;
        });

        Notification::send($users, new NearbyPlantDiscovered(plant: $this->plant, discovery: $this->discovery));
    }
}
