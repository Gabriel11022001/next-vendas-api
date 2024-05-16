<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->integer('unidades_estoque')
                ->nullable()
                ->default(0)
                ->min(0);
            $table->integer('estoque_maximo')
                ->nullable()
                ->default(0)
                ->min(0);
            $table->integer('estoque_minimo')
                ->nullable()
                ->default(0)
                ->min(0);
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->removeColumn('unidades_estoque');
            $table->removeColumn('estoque_maximo');
            $table->removeColumn('estoque_minimo');
        });
    }
};
