<?php

namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidaDadosCadastroProduto
{

    public static function validar(Request $requisicao): array|bool
    {
        $validador = Validator::make($requisicao->all(),
        [
            'nome' => 'required|unique:produtos|max:255|min:3',
            'preco_custo' => 'required|numeric|min:0',
            'percentual_desconto_em_promocao' => 'nullable|numeric|min:0|max:100',
            'unidades_estoque' => 'nullable|numeric|min:0',
            'estoque_maximo' => 'nullable|numeric|min:0',
            'estoque_minimo' => 'nullable|numeric|min:0',
            'categoria_produto_id_hash' => 'required',
        ],
        [
            'nome.required' => 'Informe o nome do produto!',
            'nome.unique' => 'Já existe um produto cadastrado com esse nome!',
            'nome.max' => 'O nome do produto deve possuir no máximo 255 caracteres!',
            'nome.min' => 'O nome do produto deve possuir no mínimo 3 caracteres!',
            'preco_custo.required' => 'Informe o preço de custo do produto!',
            'preco_custo.numeric' => 'O preço de custo do produto deve ser um dado numérico!',
            'preco_custo.min' => 'Preço de custo inválido!',
            'percentual_desconto_em_promocao.numeric' => 'O percentual de desconto do produto em promoção deve ser um dado numérico!',
            'percentual_desconto_em_promocao.max' => 'Percentual de desconto em promoção inválido!',
            'percentual_desconto_em_promocao.min' => 'Percentual de desconto em promoção inválido!',
            'categoria_produto_id.required' => 'Informe o id da categoria do produto!',
            'unidades_estoque.numeric' => 'Quantidade de unidades em estoque inválida!',
            'unidades_estoque.min' => 'Quantidade de unidades em estoque inválida!',
            'estoque_maximo.numeric' => 'Estoque máximo inválido!',
            'estoque_maximo.min' => 'Estoque máximo inválido!',
            ''
        ]);

        if ($validador->fails()) {

            return $validador->errors()->toArray();
        }

        return true;
    }
}
