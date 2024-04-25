<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoTabelaPreco extends Model
{
    use HasFactory;

    public $table = 'produto_tabela_preco';
    protected $fillable = [
        'id',
        'produto_id',
        'tabela_preco_id',
        'preco_produto'
    ];
    public $timestamps = false;
}
