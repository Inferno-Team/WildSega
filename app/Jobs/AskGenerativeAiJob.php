<?php

namespace App\Jobs;

use Log;
use Exception;
use OpenAI\Client;
use OpenAI\Factory;
use Illuminate\Support\Facades\Http;
use OpenAI\Contracts\TransporterContract;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class AskGenerativeAiJob implements ShouldQueue
{
    use Queueable;
    public static  $tries = 3;
    /**
     * Create a new job instance.
     */
    private string $plantName;
    public function __construct(private array $plantDiscoveyData, private int $discoveryId)
    {
        $this->plantName = $plantDiscoveyData['scientific_name'];
    }

    public function failed(Exception $e)
    {
        if ($this->attempts() < self::$tries) {
            $this->release(now()->addSeconds(10 * $this->attempts()));
        } else {
            Log::error("Final failure after {" . self::$tries . "} attempts: " . $e->getMessage());
        }
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $plantData = $this->getDataFromAi();

        if ($plantData) {
            dispatch(new SavePlantDataJob($plantData, $this->plantDiscoveyData, $this->discoveryId));
        }
    }

    private function getDataFromAi(): ?array
    {
        $prompt = <<<PROMPT
                    Analyze "{$this->plantName}" and return ONLY this JSON structure:
                        {
                            "tags": [5 descriptive keywords about its features/usability],
                            "harvesting_tips": "30-word practical advice for harvesting",
                            "safety_notes": "30-word warning about potential risks",
                            "description" : 30-word about this plant
                        }
                    KEY RULES:
                        1. Tags MUST describe characteristics
                        2. Tags MUST be single-word 
                        3. Tags MUST be lowercase
                    PROMPT;
        $client = (new Factory())
            ->withApiKey(config('services.deepinfra.key'))
            ->withBaseUri('https://api.deepinfra.com/v1/openai')
            ->withHttpClient(new \GuzzleHttp\Client(['timeout' => 30]))
            ->make();
        try {
            $response = $client->chat()->create([
                'model' => 'mistralai/Mistral-7B-Instruct-v0.1',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.1,
                'max_tokens' => 150,
            ]);

            // Extract and process the response
            $generatedText = $response->choices[0]->message->content;
            preg_match('/\{.*\}/s', $generatedText, $matches);

            $plantData = json_decode($matches[0] ?? '{}', true) ?? [];

            // // Filter out plant name from tags
            // $plantData['tags'] = array_values(array_filter($plantData['tags'], function ($tag) {
            //     return strtolower($tag) !== strtolower($this->plantName);
            // }));

            // Ensure exactly 5 tags
            $plantData['tags'] = array_slice($plantData['tags'], 0, 5);
            Log::info($plantData);
            return $plantData;
        } catch (\Exception $e) {
            Log::error('DeepInfra API error', [
                'error' => $e->getMessage(),
                'plant' => $this->plantName
            ]);
            return null;
        }
    }
}
