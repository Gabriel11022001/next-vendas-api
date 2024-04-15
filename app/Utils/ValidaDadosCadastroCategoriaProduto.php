<?php

namespace App\Utils;

use Illuminate\Support\Facades\Validator;

class ValidaDadosCadastroCategoriaProduto
{

    public static function validarDadosCadastroCategoriaProduto(array $dados): array|true
    {
        $validador = Validator::make($dados, [
            'descricao' => 'required|max:255|min:3'
        ],
        [
            'descricao.required' => 'Informe a descrição da categoria!',
            'descricao.max' => 'A descrição da categoria deve possuir no máximo 255 caracteres!',
            'descricao.min' => 'A descrição da categoria deve possuir no mínimo 3 caracteres!'
        ]);

        if ($validador->fails()) {

            return $validador->errors()->toArray();
        }

        return true;
    }
}
