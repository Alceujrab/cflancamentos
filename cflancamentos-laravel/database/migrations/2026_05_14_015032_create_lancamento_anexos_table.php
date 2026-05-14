<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lancamento_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lancamento_financeiro_id')
                ->constrained('lancamento_financeiros')
                ->cascadeOnDelete();
            $table->string('nome_original');
            $table->string('path');
            $table->string('mime', 100);
            $table->unsignedBigInteger('tamanho');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lancamento_anexos');
    }
};
