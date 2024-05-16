<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadeMedida extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'unidade_medida_id_hash',
        'descricao'
    ];
}
