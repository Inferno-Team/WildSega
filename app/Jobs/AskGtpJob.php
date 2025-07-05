<?php

namespace App\Jobs;

use Log;
use Exception;
use OpenAI\Factory;
use App\Models\Plant;
use App\Models\PlantDiscovery;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\NerbyDiscoverNotification;
use GuzzleHttp\Client;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class AskGtpJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $plantName,
        private readonly int $plantId,
        private readonly int $discoverId,
        private int $attempt
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $prompt = <<<ASKGPT
                    Analzye "$this->plantName" plant and return ONLY this JSON structure
                    {
                            "tags" : [ 5 descriptive keywords about this plant ],
                            "harvesting_tips" : "30-words practical advice for harvesting",
                            "safety_notes" : "30-words warning about potential risks",
                            "description" : "30-words about this plant"
                    }
                KEY RULES : 

                    1. tags MUST describe charactesistics.
                    2. tags MUST be single-words.
                    3. tags MUST be lowercase.
        ASKGPT;
        $client = (new Factory())
            ->withApiKey("PapE6I2bHzlIsjDJMoURjQ0QyyXpk5Gl")
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
                'max_tokens' => 450,
            ]);
            $generatedText = $response->choices[0]->message->content;
            $json = json_decode($generatedText, true);
            $tags = $json['tags'];
            $harvesting_tips = $json['harvesting_tips'];
            $safety_notes = $json['safety_notes'];
            $description = $json['description'];
            $plant = Plant::whereId($this->plantId)->first();
            $plant->update([
                'description' => $description,
                'harvesting_tips' => $harvesting_tips,
                'safety_notes' => $safety_notes
            ]);
            $tagsId = [];
            foreach ($tags as $tag) {
                $tagModel = Tag::firstOrCreate([
                    'name' => $tag,
                ]);
                $tagsId[] = $tagModel->id;
            }
            $plant->tags()->attach($tagsId);
            $discovery = PlantDiscovery::find($this->discoverId);
            dispatch(new NotifyUsersJob($discovery));
        } catch (Exception $e) {
            // if ($this->attempt != 3) {
            //     dispatch(new AskGtpJob($this->plantName, $this->plantId, $this->attempt + 1));
            // }
            Log::error($e->getMessage());
        }
    }
}
