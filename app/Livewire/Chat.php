<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Services\ChatbotService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Chat extends Component
{
    public $message = '';
    public Collection $conversations;
    public $isTyping = false;
    public $isOpen = false;

    protected $listeners = ['typingComplete' => 'handleTypingComplete'];



    public function mount()
    {
        $this->isOpen = true;
        $this->conversations = collect([]);
        $this->loadConversations(); // Este ok să încarci inițial
    }

    public function loadConversations()
    {
        // Încărcăm doar conversațiile complete (care au și răspuns)
        $this->conversations = Conversation::whereNotNull('response')
            ->latest()
            ->take(50)
            ->get()
            ->reverse();
    }

    public function sendMessage(ChatbotService $chatbot)
    {
        if (empty($this->message)) {
            return;
        }

        $messageText = $this->message;
        $this->message = '';

        $this->isTyping = true;
        $this->dispatch('scrollChat');

        try {
            // Salvăm mai întâi doar mesajul utilizatorului
            $conversation = new Conversation();
            $conversation->message = $messageText;
            $conversation->save();

            // Adăugăm conversația în colecție
            $this->conversations->push($conversation);

            // Obținem răspunsul
            $response = $chatbot->reply($messageText);

            $delay = $this->calculateTypingDelay($response);

            // Stocăm ID-ul conversației și răspunsul
            session(['pendingResponse' => [
                'conversation_id' => $conversation->id,
                'response' => $response
            ]]);

            $this->simulateTyping($delay);
        } catch (\Exception $e) {
            Log::error('ChatBot Error: ' . $e->getMessage());
            $this->isTyping = false;
            session()->flash('error', 'A apărut o eroare în procesarea mesajului.');
        }
    }

    public function handleTypingComplete()
    {
        $this->isTyping = false;

        if (session()->has('pendingResponse')) {
            $pendingData = session()->get('pendingResponse');

            // Găsim și actualizăm conversația în DB
            $conversation = Conversation::find($pendingData['conversation_id']);
            if ($conversation) {
                $conversation->response = $pendingData['response'];
                $conversation->save();

                // Actualizăm direct în colecția existentă
                $this->conversations = $this->conversations->map(function ($conv) use ($conversation) {
                    if ($conv->id === $conversation->id) {
                        return $conversation;
                    }
                    return $conv;
                });
            }

            session()->forget('pendingResponse');
        }

        $this->dispatch('scrollChat');
    }

    protected function calculateTypingDelay($response)
    {
        $baseDelay = strlen($response) / 20;
        return max(1, min($baseDelay, 5));
    }

    protected function simulateTyping($delay)
    {
        $this->dispatch('simulateTyping', [
            'delay' => $delay * 1000
        ]);
    }

    public function openChat()
    {
        $this->isOpen = true;
    }

    public function closeChat()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
