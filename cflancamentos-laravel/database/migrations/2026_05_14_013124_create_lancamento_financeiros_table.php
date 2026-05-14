<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lancamento_financeiros', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['receita', 'despesa']);
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('veiculo_id')->nullable()->constrained('veiculos')->nullOnDelete();
            $table->date('data');
            $table->decimal('valor', 14, 2);
            $table->string('descricao');
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->index(['tipo', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lancamento_financeiros');
    }
};
