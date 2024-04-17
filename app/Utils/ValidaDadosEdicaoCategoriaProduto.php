<?php

namespace App\Utils;

use Illuminate\Support\Facades\Validator;

class ValidaDadosEdicaoCategoriaProduto
{

    public static function validarDadosEdicaoCategoriaProduto(array $dados): bool|array
    {
        $validador = Validator::make(
            $dados,
            [
                'id_hash' => 'required',
                'descricao' => 'required|max:255|min:3',
                'ativo' => 'required|boolean'
            ],
            [
                'id_hash.required' => 'Informe o id hash da categoria!',
                'descricao.required' => 'A descrição da categoria é um dado obrigatório!',
                'descricao.max' => 'A descrição da categoria deve possuir no máximo 255 caracteres!',
                'descricao.min' => 'A descrição da categoria deve possuir no mínimo 3 caracteres!',
                'ativo.required' => 'Informe o status da categoria, (true) para ativo ou (false) para inativo!',
                'ativo.boolean' => 'O status da categoria deve ser um dado booleano!'
            ]
        );

        if ($validador->fails()) {

            return $validador->errors();
        }

        return true;
    }
}
