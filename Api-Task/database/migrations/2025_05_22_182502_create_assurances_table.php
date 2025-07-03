<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assurances', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('numero_police')->unique();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('montant', 12, 2);
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assurances');
    }
}; 