<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\ChatbotTraining;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    private $baseContext = [
        [
            'role' => 'system',
            'content' => 'Ești un asistent AI prietenos și profesionist. Răspunzi în limba română.'
        ]
    ];

    private function getTrainingContext()
    {
        return ChatbotTraining::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get()
            ->map(function($training) {
                return [
                    'role' => 'system',
                    'content' => $training->content
                ];
            })
            ->toArray();
    }

    public function reply($message)
    {
        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => array_merge(
                    $this->baseContext,
                    $this->getTrainingContext(),
                    [['role' => 'user', 'content' => $message]]
                ),
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            $responseText = $result->choices[0]->message->content;

            Conversation::create([
                'message' => $message,
                'response' => $responseText
            ]);

            return $responseText;
            
        } catch (\Exception $e) {
            Log::error('OpenAI Error: ' . $e->getMessage());
            throw new \Exception('Nu am putut procesa mesajul. Te rog încearcă din nou.');
        }
    }
}