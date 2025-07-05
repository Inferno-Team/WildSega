<?php

namespace App\Jobs;

use Exception;
use App\Models\Tag;
use App\Models\User;
use App\Models\Plant;
use App\Models\PlantDiscovery;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NearbyPlantDiscovered;

class AskPlantAIJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly PlantDiscovery $discovery) {}
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fullImagePath = $this->discovery->media->first()?->getPath();
        if (!file_exists($fullImagePath)) {
            Log::error("PlantNet image not found: {$fullImagePath}");
            return;
        }
        $aiResponse = $this->askPlantNetAI(fullImagePath: $fullImagePath);

        if (!$aiResponse || empty($aiResponse['scientific_name'])) {
            Log::error("aiResponse error: {$aiResponse}");
            return;
        }
        Log::info("aiResponse: " . json_encode($aiResponse));
        $plants = Plant::whereScientificName($aiResponse['scientific_name'])->get();
        if ($plants->isEmpty()) {
            dispatch(new AskGenerativeAiJob($aiResponse, $this->discovery->id));
        } else {
            $plant = $plants->first();
            // $discovery = PlantDiscovery::wherePlantId($plant->id)->first();
            $tags = $plant->tags;
            dispatch(new NotifyUsersNewPlantDiscoveryJob(
                $this->discovery,
                $plant,
                collect($tags)->pluck('id')->toArray()
            ));
        }
        /*  DB::transaction(function () use ($aiResponse) {
            // === 2. Create plant if not exists ===
            $plant = Plant::firstOrCreate([
                'scientific_name' => $aiResponse['scientific_name'],
            ], [
                'common_name' => $aiResponse['common_name'] ?? null,
                'description' => $aiResponse['description'] ?? null,
                'safety_notes' => $aiResponse['safety_notes'] ?? null,
                'harvesting_tips' => $aiResponse['harvesting_tips'] ?? null,
            ]);

            $this->discovery->plant()->associate($plant);
            $this->discovery->save();

            // === 3. Handle tags ===
            $tagNames = $aiResponse['tags'] ?? [];
            $tags = collect($tagNames)->map(function ($tagName) {
                return Tag::firstOrCreate(['name' => $tagName], ['type' => 'category']);
            });

            $plant->tags()->syncWithoutDetaching($tags->pluck('id')->toArray());

            // === 4. Notify users who prefer these tags AND are nearby ===
            $this->notifyRelevantUsers($tags->pluck('id')->toArray(), $plant);
        }); */
    }
    private function askPlantNetAI(string $fullImagePath): ?array
    {
        $apiKey = config('plantnet.key');
        $lang = config('plantnet.lang');
        $nbResults = config('plantnet.nb-results');
        $url = "https://my-api.plantnet.org/v2/identify/all?api-key={$apiKey}&lang={$lang}&nb-results={$nbResults}";
        $response = Http::attach(
            'images',
            file_get_contents($fullImagePath),
            basename($fullImagePath)
        )->post($url);

        if ($response->successful()) {
            $result = $response->json();
            $best = collect($result['results'])->first();
            if ($best) {
                $scientificName = $best['species']['scientificName'] ?? null;
                $commonNames = $best['species']['commonNames'] ?? [];
                $score = $best['score'];
                $genus = $best['species']['genus']['scientificName'] ?? null;
                $family = $best['species']['family']['scientificName'] ?? null;
                return  [
                    'scientific_name' => $scientificName,
                    'score' => $score,
                    'genus' => $genus,
                    'family' => $family,
                    'common_names' => $commonNames,
                ];
            } else {
                Log::warning('No plant match found in PlantNet response , discovery item : ' . $this->discovery->id);
                return null;
            }
        } else {
            Log::error('PlantNet API error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'discovery' => $this->discovery->id,
            ]);
            return null;
        }
    }


    protected function notifyRelevantUsers(array $tagIds, Plant $plant): void
    {
        $discoveryLocation = (object)[
            'latitude' => $this->discovery->latitude,
            'longitude' => $this->discovery->longitude
        ];

        if (!$discoveryLocation) return;

        $users = User::whereHas('preferences', function ($q) use ($tagIds) {
            $q->whereIn('tag_id', $tagIds);
        })->get();

        foreach ($users as $user) {
            $distance = $this->calculateDistance(
                $discoveryLocation->latitude,
                $discoveryLocation->longitude,
                $user->latitude,
                $user->longitude
            );

            if ($distance <= $user->notification_range_km) {
                $user->notify(new NearbyPlantDiscovered($plant, $this->discovery));
            }
        }
    }
    // Haversine formula to calculate distance between two points
    protected function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return $earthRadius * $angle;
    }
}
