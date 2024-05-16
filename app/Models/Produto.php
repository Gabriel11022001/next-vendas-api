<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'nome',
        'codigo_barras',
        'descricao',
        'preco_custo',
        'status',
        'percentual_desconto_em_promocao',
        'categoria_produto_id',
        'sub_categoria_produto_id',
        'unidade_medida_id',
        'produto_id_hash',
        'data_cadastro',
        'em_promocao',
        'unidades_estoque',
        'estoque_maximo',
        'estoque_minimo'
    ];
}
