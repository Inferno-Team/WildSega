<?php

namespace App\Jobs;

use App\Models\Tag;
use App\Models\User;
use App\Models\Plant;
use App\Models\PlantDiscovery;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NearbyPlantDiscovered;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use App\Jobs\NotifyUsersNewPlantDiscoveryJob;

class SavePlantDataJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $plantData,
        private array $plantDiscoveyData,
        private int $discoveryId
    ) {
        //
    }

    //plantDiscoveyData => [scientific_name,score,genus,family,common_names]
    //plantData => [tags , harvesting_tips,safety_notes,description] 
    public function handle(): void
    {
        $discovery = PlantDiscovery::whereId($this->discoveryId)->first();
        $user = $discovery->user;

        $plant = Plant::create([
            'common_name' => array_key_exists('common_names', $this->plantDiscoveyData) ?
                (count($this->plantDiscoveyData['common_names']) > 0 ?
                    $this->plantDiscoveyData['common_names'][0] : $this->plantDiscoveyData['scientific_name']
                ) : $this->plantDiscoveyData['scientific_name'],
            'scientific_name' =>  $this->plantDiscoveyData['scientific_name'],
            'description' => $this->plantData['description'],
            'safety_notes' => $this->plantData['safety_notes'],
            'harvesting_tips' => $this->plantData['harvesting_tips'],
            'status' => Plant::STATUS_PENDING,
        ]);
        $discovery->update([
            'ai_confidence_score' => $this->plantDiscoveyData['score'],
            'plant_id' => $plant->id
        ]);
        if ($firstImage = $discovery->getMedia('images')->first()) {
            $plant->addMedia($firstImage->getPath())
                ->preservingOriginal()
                ->toMediaCollection('images');
        }
        $tags = [];
        foreach ($this->plantData['tags'] as $tag) {
            $tagModel = Tag::firstOrCreate(['name' => $tag, 'type' => Tag::TYPE_CATEGORY]);
            $tags[] = $tagModel;
            $plant->tags()->attach($tagModel);
        }
        dispatch(new NotifyUsersNewPlantDiscoveryJob(
            $discovery,
            $plant,
            collect($tags)->pluck('id')->toArray()
        ));
    }
}
