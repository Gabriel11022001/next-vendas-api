<?php

namespace App\Utils;

use Illuminate\Support\Facades\Validator;

class ValidaDadosEdicaoSubCategoriaProduto
{

    public static function validarDadosEdicaoSubCategoria(array $dados): bool|array
    {
        $validador = Validator::make($dados,
        [
            'descricao' => 'required|max:255|min:3',
            'id_hash' => 'required',
            'categoria_produto_id' => 'required|numeric',
            'ativo' => 'required|boolean'
        ],
        [
            'descricao.required' => 'Informe a descrição da sub-categoria!',
            'descricao.max' => 'A sub-categoria do produto deve possuir no máximo 255 caracteres!',
            'descricao.min' => 'A sub-categoria do produto deve possuir no mínimo 3 caracteres!',
            'id_hash.required' => 'Informe o id da sub-categoria!',
            'categoria_produto_id.required' => 'Informe o id da categoria!',
            'categoria_produto_id.numeric' => 'O id da categoria do produto deve ser um valor numérico!',
            'ativo.required' => 'Informe o status da sub-categoria!',
            'ativo.boolean' => 'O status da categoria deve ser um dado booleano, ativo(true) ou inativo(false)!'
        ]);

        return $validador->fails() ? $validador->errors() : true;
    }
}
