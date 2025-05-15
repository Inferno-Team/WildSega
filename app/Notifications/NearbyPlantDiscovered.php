<?php

namespace App\Notifications;

use App\Models\Plant;
use App\Models\PlantDiscovery;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NearbyPlantDiscovered extends Notification implements ShouldQueue
{
    use Queueable;


    public function __construct(private Plant $plant, private PlantDiscovery $discovery) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'plant_id' => $this->plant->id,
            'plant_name' => $this->plant->scientific_name,
            'discovery_id' => $this->discovery->id,
            'message' => "New plant '{$this->plant->scientific_name}' discovered near you.",
        ];
    }
}
