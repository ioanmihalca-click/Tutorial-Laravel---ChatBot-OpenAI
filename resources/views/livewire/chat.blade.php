<!-- Title Section -->

<div class="w-full py-12 mb-8 bg-gradient-to-r from-blue-600 to-blue-800">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                <span class="block">Tutorial Laravel:</span>
                <span class="block mt-2 text-blue-200">Cum să construiești un ChatBot AI cu Laravel și OpenAI</span>
            </h1>
            <p class="max-w-3xl mx-auto mt-4 text-xl text-blue-100">
                Învață să implementezi un chatbot inteligent folosind Laravel și API-ul OpenAI
            </p>
        </div>
    </div>

<!-- Chatbot section -->

<div class="fixed z-50 bottom-4 right-4">
    <div class="relative">
        @if ($isOpen)
            <div class="bg-white rounded-lg shadow-xl w-96 h-[600px] flex flex-col chat-slide-in">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 bg-blue-600 rounded-t-lg">
                    <h2 class="font-bold text-white">Click Studios Digital Assistant</h2>
                    <button wire:click="closeChat" class="text-white hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Messages Container -->
                <div class="flex-1 p-4 space-y-4 overflow-y-auto" 
                     id="chat-container" 
                     style="max-height: calc(100% - 130px); scroll-behavior: smooth;">
                    <!-- Welcome message -->
                    @if ($conversations->isEmpty())
                        <div class="flex justify-start">
                            <div class="bg-gray-100 rounded-lg p-3 max-w-[80%]">
                                Bună! Sunt aici să te ajut cu informații despre Click Studios Digital și serviciile
                                noastre. Cu ce te pot ajuta?
                            </div>
                        </div>
                    @endif

                    <!-- Conversations -->
                    @foreach ($conversations as $conversation)
                        <div class="space-y-2">
                            <!-- User message -->
                            <div class="flex justify-end">
                                <div class="bg-blue-500 text-white rounded-lg py-2 px-4 max-w-[80%]">
                                    {{ $conversation->message }}
                                </div>
                            </div>

                            <!-- Bot response -->
                            @if ($conversation->response)
                                <div class="flex justify-start">
                                    <div class="bg-gray-100 rounded-lg py-2 px-4 max-w-[80%]">
                                        {{ $conversation->response }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Typing indicator -->
                    @if ($isTyping)
                        <div class="flex justify-start">
                            <div class="p-3 bg-gray-100 rounded-lg">
                                <div class="flex space-x-2">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Input Form -->
                <div class="p-4 border-t">
                    <form wire:submit.prevent="sendMessage" class="flex space-x-2">
                        <input type="text" 
                               wire:model.live="message"
                               class="flex-1 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                               placeholder="Scrie un mesaj..."
                               @if($isTyping) disabled @endif>
                        <button type="submit"
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                                @if($isTyping) disabled @endif
                                wire:loading.attr="disabled">
                            Trimite
                        </button>
                    </form>
                </div>
            </div>
        @else
            <button 
                wire:click="openChat"
                class="p-4 transition-all duration-200 bg-blue-600 rounded-full shadow-lg hover:bg-blue-700 hover:scale-110"
            >
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    class="w-8 h-8 text-white"
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"
                >
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    <path d="M8 10h.01"></path>
                    <path d="M12 10h.01"></path>
                    <path d="M16 10h.01"></path>
                </svg>
            </button>
        @endif
    </div>
</div>

</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('simulateTyping', (data) => {
            setTimeout(() => {
                Livewire.dispatch('typingComplete');
            }, data[0].delay);
        });

        Livewire.on('scrollChat', () => {
            requestAnimationFrame(() => {
                const container = document.getElementById('chat-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        });

        // Scroll automat când se încarcă pagina
        const container = document.getElementById('chat-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>