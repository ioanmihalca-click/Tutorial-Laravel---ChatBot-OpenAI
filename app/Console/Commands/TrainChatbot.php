<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChatbotTraining;

class TrainChatbot extends Command
{
    protected $signature = 'chatbot:train {--fresh : Șterge training-ul vechi}';
    protected $description = 'Train the chatbot with company specific information';

    public function handle()
    {
        if ($this->option('fresh')) {
            ChatbotTraining::query()->delete();
            $this->info('Old training data deleted.');
        }

        $trainingData = [
            [
                'content' => 'Numele companiei este Click Studios Digital, cu sediul în Baia-Mare, Maramureș.',
                'category' => 'company_info',
                'priority' => 100
            ],
            [
                'content' => 'Transformăm idei în experiențe digitale memorabile.',
                'category' => 'mission',
                'priority' => 95
            ],
            [
                'content' => 'Suntem specializați în dezvoltare Laravel și integrări AI pentru soluții web inovatoare.',
                'category' => 'services',
                'priority' => 90
            ],
            [
                'content' => 'Oferim servicii de Web Development, Digital Marketing, și soluții e-commerce avansate.',
                'category' => 'services',
                'priority' => 85
            ],
            [
                'content' => 'Programul de lucru este Luni-Vineri între orele 9:00-17:00.',
                'category' => 'schedule',
                'priority' => 90
            ],
            [
                'content' => 'Contactează-ne la contact@clickstudios-digital.com sau la telefon +4 0734 411 115.',
                'category' => 'contact',
                'priority' => 100
            ],
            [
                'content' => '1. Dezvoltare Laravel: Aplicații robuste, API-uri RESTful, Sisteme CRM.',
                'category' => 'services',
                'priority' => 80
            ],
            [
                'content' => '2. Web Design Modern: UI/UX Design, Optimizare Mobile First, Optimizare UX.',
                'category' => 'services',
                'priority' => 80
            ],
            [
                'content' => '3. Marketing Digital: SEO, Content Marketing, Social Media, Google Ads.',
                'category' => 'services',
                'priority' => 80
            ],
            [
                'content' => '4. Spoturi Publicitare: Reclame TV și online, Producție audio, Voice-over.',
                'category' => 'services',
                'priority' => 80
            ],
            [
                'content' => '5. Soluții E-commerce: PrestaShop, Bagisto Laravel, Integrări plăți.',
                'category' => 'services',
                'priority' => 80
            ],
            [
                'content' => '6. Integrare AI & ML: Chatboți AI, Automatizări, Analiză predictivă.',
                'category' => 'services',
                'priority' => 80
            ],
            [
                'content' => 'Suntem disponibili pentru consultanță gratuită, oferind suport tehnic 24/7.',
                'category' => 'support',
                'priority' => 85
            ],
            [
                'content' => 'Proiectele noastre includ peste 50 de realizări finalizate cu succes, cu o satisfacție a clienților de 100%.',
                'category' => 'achievements',
                'priority' => 75
            ],
            [
                'content' => 'Valorile noastre includ profesionalismul, inovația și suportul dedicat pentru fiecare proiect.',
                'category' => 'values',
                'priority' => 70
            ],
            [
                'content' => 'Prețurile pentru serviciile noastre de dezvoltare web încep de la X EUR, iar soluțiile avansate sunt personalizate în funcție de cerințele clientului. Contactează-ne pentru o ofertă detaliată.',
                'category' => 'pricing',
                'priority' => 90
            ],
            // Poți adăuga oricâte instrucțiuni dorești
        ];

        foreach ($trainingData as $data) {
            ChatbotTraining::create($data);
        }

        $this->info('Chatbot has been trained with ' . count($trainingData) . ' instructions!');
    }
}