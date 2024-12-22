// database/migrations/xxxx_create_chatbot_training_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_training', function (Blueprint $table) {
            $table->id();
            $table->text('content');        // instrucțiunea de training
            $table->string('category')->nullable();  // categorie pentru organizare
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // prioritate pentru ordinea instrucțiunilor
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_training');
    }
};