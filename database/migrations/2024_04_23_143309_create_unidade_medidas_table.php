<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('unidade_medidas', function (Blueprint $table) {
            $table->id();
            $table->string('unidade_medida_id_hash')
                ->nullable(false)
                ->unique();
            $table->string('descricao')
                ->nullable(false)
                ->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidade_medidas');
    }
};
