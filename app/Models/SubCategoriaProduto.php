<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoriaProduto extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'sub_categoria_id_hash', 'descricao', 'ativo', 'categoria_produto_id'];

    public function categoria()
    {

        return $this->belongsTo(CategoriaProduto::class);
    }
}
