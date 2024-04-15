<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sub_categoria_produtos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao')
                ->nullable(false);
            $table->boolean('ativo')
                ->nullable(false)
                ->default(true);
            $table->unsignedBigInteger('categoria_produto_id')
                ->nullable(false);
            $table->foreign('categoria_produto_id')
                ->references('id')
                ->on('categoria_produtos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_categoria_produtos');
    }
};
