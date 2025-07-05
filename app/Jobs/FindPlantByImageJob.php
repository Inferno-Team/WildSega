<?php

namespace App\Jobs;

use App\Models\Plant;
use App\Jobs\NotifyUsersJob;
use App\Models\PlantDiscovery;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FindPlantByImageJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $imagePath, private readonly int $discoveryId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $key = config('plant.key');
        $lang = config('plant.lang');
        $nbResult = config('plant.nb_result');
        $url = "https://my-api.plantnet.org/v2/identify/all?api-key=$key&lang=$lang&nb-results=$nbResult";
        $response = Http::attach(
            "images",
            file_get_contents($this->imagePath),
            basename($this->imagePath)
        )->post($url);
        if ($response->successful()) {
            $jsonData = $response->json();
            $best = $jsonData['results'];
            if (count($best) > 0) {
                $bestPlantResult = $best[0];
                $sName = $bestPlantResult['species']['scientificName'];
                $commonNames = $bestPlantResult['species']['commonNames'];
                $cName = count($commonNames) > 0 ? $commonNames[0] : null;
                $description = "";
                $plant = Plant::whereScientificName($sName)->first();
                $discovery = PlantDiscovery::find($this->discoveryId);
                if (!$plant) {
                    $plant = Plant::create([
                        'common_name' => $cName,
                        'scientific_name' => $sName,
                        'description' => $description,
                    ]);
                    $plant->addMedia($this->imagePath)->preservingOriginal()->toMediaCollection('images');
                    // fire new job to find the plant's tags.
                    dispatch(new AskGtpJob($plant->scientific_name, $plant->id, $this->discoveryId, 0));
                } else {
                    dispatch(new NotifyUsersJob($discovery));
                }
                $discovery->update(['plant_id' => $plant->id]);
            }
        }
    }
}
