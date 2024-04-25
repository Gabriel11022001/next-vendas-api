<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('produto_id_hash')
                ->nullable()
                ->unique();
            $table->string('nome')
                ->nullable(false)
                ->unique()
                ->max(255)
                ->min(3);
            $table->text('codigo_barras')
                ->nullable();
            $table->text('descricao')
                ->nullable();
            $table->decimal('preco_custo')
                ->nullable(false)
                ->min(0);
            $table->boolean('status')
                ->nullable(false)
                ->default(true);
            $table->string('data_cadastro')
                ->nullable(false);
            $table->boolean('em_promocao')
                ->nullable(false)
                ->default(false);
            $table->decimal('percentual_desconto_em_promocao')
                ->nullable();
            $table->unsignedBigInteger('categoria_produto_id')
                ->nullable(false);
            $table->unsignedBigInteger('sub_categoria_produto_id')
                ->nullable();
            $table->unsignedBigInteger('unidade_medida_id')
                ->nullable();
            $table->foreign('categoria_produto_id')
                ->references('id')
                ->on('categoria_produtos');
            $table->foreign('sub_categoria_produto_id')
                ->references('id')
                ->on('sub_categoria_produtos');
            $table->foreign('unidade_medida_id')
                ->references('id')
                ->on('unidade_medidas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
