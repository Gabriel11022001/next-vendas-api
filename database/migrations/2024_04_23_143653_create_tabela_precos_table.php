<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tabela_precos', function (Blueprint $table) {
            $table->id();
            $table->string('tabela_preco_id_hash')
                ->nullable(false)
                ->unique();
            $table->string('titulo')
                ->nullable(false)
                ->unique();
            $table->boolean('ativo')
                ->nullable(false)
                ->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabela_precos');
    }
};
