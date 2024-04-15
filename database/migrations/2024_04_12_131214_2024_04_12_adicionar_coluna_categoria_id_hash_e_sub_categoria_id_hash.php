<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        // adicionar coluna categoria_id_hash na tabela categoria_produtos
        Schema::table('categoria_produtos', function (Blueprint $table) {
            $table->string('categoria_id_hash')
                ->nullable(false)
                ->unique();
        });
        // adicionar a coluna sub_categoria_id_hash na tabela sub_categoria_produtos
        Schema::table('sub_categoria_produtos', function (Blueprint $table) {
            $table->string('sub_categoria_id_hash')
                ->nullable(false)
                ->unique();
        });
    }

    public function down(): void
    {
        // remover as colunas das tabelas
        Schema::dropColumns('categoria_produtos', 'categoria_id_hash');
        Schema::dropColumns('sub_categoria_produtos', 'sub_categoria_id_hash');
    }
};
