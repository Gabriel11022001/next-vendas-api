<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelaPreco extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'tabela_preco_id_hash',
        'ativo',
        'titulo'
    ];
}
