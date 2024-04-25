<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('produto_tabela_preco', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id')
                ->nullable(false);
            $table->unsignedBigInteger('tabela_preco_id')
                ->nullable(false);
            $table->decimal('preco_produto')
                ->nullable(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produto_tabela_preco');
    }
};
