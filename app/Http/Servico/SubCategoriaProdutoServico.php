<?php

namespace App\Http\Servico;

use App\Models\CategoriaProduto;
use App\Models\SubCategoriaProduto;
use App\Utils\Log;
use App\Utils\Response;
use App\Utils\ValidaDadosCadastroSubCategoriaProduto;
use App\Utils\ValidaDadosEdicaoSubCategoriaProduto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SubCategoriaProdutoServico implements IServicoSubCategoriaProduto
{

    public function salvar(Request $requisicao): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validador = ValidaDadosCadastroSubCategoriaProduto::validarDadosCadastroSubCategoria($requisicao->all());

            if (is_array($validador)) {

                return Response::response(
                    'Ocorreram erros de validação de dados!',
                    $validador,
                    false,
                    200
                );
            }

            $subCategoriaCadastradaComDescricaoInformada = SubCategoriaProduto::where('descricao', $requisicao->descricao)
                ->get()
                ->toArray();

            if ($subCategoriaCadastradaComDescricaoInformada) {

                return Response::response(
                    'Já existe uma sub-categoria cadastrada com essa descrição!',
                    [],
                    false,
                    200
                );
            }

            if (!CategoriaProduto::find($requisicao->categoria_produto_id)) {

                return Response::response(
                    'Não existe uma categoria cadastrada com esse id no banco de dados!',
                    [],
                    false,
                    200
                );
            }

            $subCategoria = new SubCategoriaProduto();
            $subCategoria->descricao = $requisicao->descricao;
            $subCategoria->categoria_produto_id = $requisicao->categoria_produto_id;
            $subCategoria->sub_categoria_id_hash = '';

            if (!$subCategoria->save()) {

                return Response::response(
                    'Ocorreu um erro ao tentar-se cadastrar a sub-categoria no banco de dados!',
                    [],
                    false,
                    200
                );
            }

            $subCategoria->sub_categoria_id_hash = md5($subCategoria->id);

            if (!$subCategoria->save()) {
                DB::rollBack();

                return Response::response(
                    'Ocorreu um erro ao tentar-se cadastrar a sub-categoria no banco de dados!',
                    [],
                    false,
                    200
                );
            }

            DB::commit();

            return Response::response(
                'Sub-categoria de produto cadastrada com sucesso!',
                [
                    'id_hash' => $subCategoria->sub_categoria_id_hash,
                    'descricao' => $subCategoria->descricao,
                    'ativo' => true,
                    'categoria' => DB::query()
                    ->from('categoria_produtos')
                    ->select(
                        'categoria_id_hash',
                        'descricao'
                    )
                    ->where('id', $subCategoria->categoria_produto_id)
                    ->get()
                    ->toArray()
                ],
                true,
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::registrar(
                $e->getMessage(),
                $requisicao->all(),
                true,
                'Cadastrar sub-categoria de produto'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se cadastrar a sub-categoria no banco de dados!',
                [],
                false,
                200
            );
        }

    }

    public function editarSubCategoriaProduto(Request $requisicao): JsonResponse
    {

        try {
            $validador = ValidaDadosEdicaoSubCategoriaProduto::validarDadosEdicaoSubCategoria($requisicao->all());

            if (is_array($validador)) {

                return Response::response(
                    'Ocorreram erros de validação de dados!',
                    $validador,
                    false,
                    200
                );
            }

            $subCategoria = SubCategoriaProduto::where('sub_categoria_id_hash', $requisicao->id_hash)
                ->first();

            if (!$subCategoria) {

                return Response::response(
                    'Não existe uma sub-categoria cadastrada com o id informado!',
                    [],
                    true,
                    200
                );
            }

            $subCategoriaComDescricaoInformada = SubCategoriaProduto::where('descricao', $requisicao->descricao)
                ->first();

            if ($subCategoriaComDescricaoInformada != null) {

                if ($subCategoriaComDescricaoInformada->sub_categoria_id_hash != $requisicao->id_hash) {

                    return Response::response(
                        'Já existe uma outra sub-categoria cadastrada com a descrição informada!',
                        [],
                        false,
                        200
                    );
                }

            }

            $categoriaRelacionada = CategoriaProduto::find($requisicao->categoria_produto_id);

            if (!$categoriaRelacionada) {

                return Response::response(
                    'Não existe uma categoria cadastrada com o id informado!',
                    [],
                    false,
                    200
                );
            }

            $subCategoria->descricao = $requisicao->descricao;
            $subCategoria->ativo = $requisicao->ativo;
            $subCategoria->categoria_produto_id = $requisicao->categoria_produto_id;

            if (!$subCategoria->save()) {

                return Response::response(
                    'Ocorreu um erro ao tentar-se editar a sub-categoria do produto!',
                    [],
                    false,
                    200
                );
            }

            return Response::response(
                'Sub-categoria editada com sucesso!',
                $requisicao->all(),
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                $requisicao->all(),
                true,
                'Editar sub-categoria de produto'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se editar a sub-categoria do produto!',
                [],
                false,
                200
            );
        }

    }

    public function listarTodos(): JsonResponse
    {
        try {
            $subCategoriasProdutos = SubCategoriaProduto::join('categoria_produtos', 'categoria_produtos.id', '=', 'sub_categoria_produtos.categoria_produto_id')
                ->select([
                    'sub_categoria_produtos.sub_categoria_id_hash AS id_hash_sub_categoria',
                    'sub_categoria_produtos.descricao AS sub_categoria',
                    'categoria_produtos.descricao AS categoria',
                    'sub_categoria_produtos.ativo'
                ])
                ->get();

            foreach ($subCategoriasProdutos as $subCategoria) {
                $subCategoria->ativo = $subCategoria->ativo ? true : false;
            }

            if (count($subCategoriasProdutos->toArray()) > 0) {

                return Response::response(
                    'Sub-categorias encontradas com sucesso!',
                    $subCategoriasProdutos->toArray(),
                    true,
                    200
                );
            }

            return Response::response(
                'Não existem sub-categorias cadastradas no banco de dados!',
                [],
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                [],
                true,
                'Listar todas as sub-categorias!'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se listar todas as sub-categorias de produtos!',
                [],
                false,
                200
            );
        }

    }

    public function buscarSubCategoriaProdutoPeloId(string $idHash): JsonResponse
    {

        try {
            $subCategoria = SubCategoriaProduto::join('categoria_produtos', 'categoria_produtos.id', '=', 'sub_categoria_produtos.categoria_produto_id')
                ->select([
                    'sub_categoria_produtos.sub_categoria_id_hash AS id_hash_sub_categoria',
                    'sub_categoria_produtos.descricao AS sub_categoria',
                    'categoria_produtos.descricao AS categoria',
                    'sub_categoria_produtos.ativo'
                ])
                ->where('sub_categoria_produtos.sub_categoria_id_hash', '=', $idHash)
                ->first();

                if (!$subCategoria) {

                    return Response::response(
                        'Não existe uma sub-categoria cadastrada com esse id!',
                        [],
                        true,
                        200
                    );
                }

                $subCategoria->ativo = $subCategoria->ativo ? true : false;

                return Response::response(
                    'Sub-categoria encontrada com sucesso!',
                    $subCategoria->toArray(),
                    true,
                    200
                );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                [
                    'id_hash_sub_categoria' => $idHash
                ],
                true,
                'Consultar sub-categoria pelo id'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se buscar os dados da sub-categoria!',
                [],
                false,
                200
            );
        }

    }

    public function alterarStatusSubCategoriaProduto(Request $requisicao): JsonResponse
    {

    }
}
