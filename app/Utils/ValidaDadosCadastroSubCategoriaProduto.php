<?php

namespace App\Utils;

use Illuminate\Support\Facades\Validator;

class ValidaDadosCadastroSubCategoriaProduto
{

    public static function validarDadosCadastroSubCategoria(array $dados): array|bool
    {
        $validador = Validator::make($dados, [
            'descricao' => 'required|max:255|min:3',
            'categoria_produto_id' => 'required|numeric'
        ],
        [
            'descricao.required' => 'Informe a descrição da sub-categoria!',
            'descricao.max' => 'A descrição da sub-categoria deve possuir no máximo 255 caracteres!',
            'descricao.min' => 'A descrição da sub-categoria deve possuir no mínimo 3 caracteres!',
            'categoria_produto_id.required' => 'Informe o id da categoria do produto!',
            'categoria_produto.numeric' => 'A categoria do produto deve ser um dado numérico!'
        ]);

        return $validador->fails() ? $validador->errors() : true;
    }
}
