<?php

namespace App\Http\Servico;

use App\Models\CategoriaProduto;
use App\Utils\Log;
use App\Utils\Response;
use App\Utils\ValidaDadosCadastroCategoriaProduto;
use App\Utils\ValidaDadosEdicaoCategoriaProduto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CategoriaProdutoServico implements IServicoCategoriaProduto
{

    public function salvar(Request $requisicao): JsonResponse
    {
        DB::beginTransaction();

        try {
            Log::registrar(
                '',
                $requisicao->all(),
                false,
                'Cadastrar categoria de produto'
            );
            $validacaoDadosCadastroCategoria = ValidaDadosCadastroCategoriaProduto::validarDadosCadastroCategoriaProduto($requisicao->all());

            if (is_array($validacaoDadosCadastroCategoria)) {

                return Response::response(
                    'Ocorreram erros de validação de dados!',
                    $validacaoDadosCadastroCategoria,
                    false,
                    200
                );
            }

            $categoriaProduto = new CategoriaProduto();
            $categoriaProduto->descricao = $requisicao->descricao;
            $categoriaProduto->categoria_id_hash = '';

            if ($categoriaProduto->save()) {
                $categoriaProduto->categoria_id_hash = md5($categoriaProduto->id);

                if ($categoriaProduto->save()) {
                    DB::commit();

                    return Response::response(
                        'Categoria cadastrada com sucesso!',
                        [
                            'id_hash' => $categoriaProduto->categoria_id_hash,
                            'descricao' => $categoriaProduto->descricao,
                            'ativo' => true
                        ],
                        true,
                        201
                    );
                } else {
                    DB::rollBack();

                    return Response::response(
                        'Ocorreu um erro ao tentar-se cadastrar a categoria, tente novamente!',
                        [],
                        200,
                        false
                    );
                }

            } else {

                return Response::response(
                    'Ocorreu um erro ao tentar-se cadastrar a categoria, tente novamente!',
                    [],
                    200,
                    false
                );
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::registrar(
                $e->getMessage(),
                $requisicao->all(),
                true,
                'Cadastrar categoria de produto'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se cadastrar a categoria, tente novamente!',
                [],
                false,
                200
            );
        }

    }

    public function listarTodos(): JsonResponse
    {

        try {
            $categoriasProduto = DB::query()
                ->select([
                    'categoria_id_hash AS id_hash',
                    'descricao',
                    'ativo'
                ])
                ->from('categoria_produtos')
                ->orderBy('descricao')
                ->get()
                ->toArray();

            if (!$categoriasProduto) {

                return Response::response(
                    'Não existem categorias cadastradas no banco de dados!',
                    [],
                    true,
                    200
                );
            }

            foreach ($categoriasProduto as $categoria) {
                $categoria->ativo = $categoria->ativo === 1 ? true : false;
            }

            return Response::response(
                'Categorias encontradas com sucesso!',
                $categoriasProduto,
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                [],
                true,
                'Listar todas as categorias de produto'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se listas todas as categorias de produtos!',
                [],
                false,
                200
            );
        }

    }

    public function buscarCategoriaProdutoPeloId(string $idHash): JsonResponse
    {

        try {
            $categoria = CategoriaProduto::where('categoria_id_hash', $idHash)
                ->select([
                    'categoria_id_hash',
                    'descricao',
                    'ativo'
                ])
                ->get()
                ->toArray();

            if (!$categoria) {

                return Response::response(
                    'Não existe uma categoria cadastrada no banco de dados com esse id!',
                    [],
                    true,
                    200
                );
            }

            $categoria[0]['ativo'] = $categoria[0]['ativo'] === 1 ? true : false;

            return Response::response(
                'Categoria encontrada com sucesso!',
                $categoria,
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                [
                    'categoria_id' => $idHash
                ],
                true,
                'Buscar categoria do produto pelo id'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se buscar a categoria pelo id!',
                [],
                false,
                200
            );
        }

    }

    public function alterarStatusCategoriaProduto(Request $requisicao): JsonResponse
    {
        Log::registrar(
            '',
            $requisicao->all(),
            false,
            'Alterar status da categoria'
        );

        try {
            $categoria = CategoriaProduto::where('categoria_id_hash', $requisicao->id_hash)
                ->get()
                ->first();

            if (!$categoria) {

                return Response::response(
                    'Não existe uma categoria cadastrada com esse id!',
                    [],
                    true,
                    200
                );
            }

            $categoria->ativo = !$requisicao->status_atual;

            if ($categoria->save()) {

                return Response::response(
                    'O status da categoria foi alterado com sucesso!',
                    [
                        'id_hash' => $categoria->categoria_id_hash,
                        'descricao' => $categoria->descricao,
                        'novo_status' => $categoria->ativo
                    ],
                    true,
                    200
                );
            }

            return Response::response(
                'Ocorreu um erro ao tentar-se alterar o status da categoria!',
                $requisicao->all(),
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                $requisicao->all(),
                true,
                'Alterar status da categoria'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se alterar o status da categoria!',
                [
                    'categoria_id' => $requisicao->id_hash,
                    'status_atual' => $requisicao->status_atual,
                    'novo_status' => !$requisicao->status_atual
                ],
                false,
                200
            );
        }

    }

    public function editarCategoriaProduto(Request $requisicao): JsonResponse
    {

        try {
            $validador = ValidaDadosEdicaoCategoriaProduto::validarDadosEdicaoCategoriaProduto($requisicao->all());

            if (is_array($validador)) {

                return Response::response(
                    'Ocorreram erros de validação de dados!',
                    $validador,
                    false,
                    200
                );
            }

            $categoria = CategoriaProduto::where('categoria_id_hash', $requisicao->id_hash)
                ->first();

            if (!$categoria) {

                return Response::response(
                    'Não existe uma categoria cadastrada no banco de dados com o id informado!',
                    [],
                    true,
                    200
                );
            }

            $categoriaConsultadaPelaDescricao = CategoriaProduto::where('descricao', $requisicao->descricao)
                ->first();

            if ($categoriaConsultadaPelaDescricao != null) {

                if ($categoriaConsultadaPelaDescricao->categoria_id_hash != $requisicao->id_hash) {

                    return Response::response(
                        'Já existe uma outra categoria cadastrada com essa descrição!',
                        [],
                        false,
                        200
                    );
                }

            }

            $categoria->descricao = $requisicao->descricao;
            $categoria->ativo = $requisicao->ativo;

            if (!$categoria->save()) {

                return Response::response(
                    'Ocorreu um erro ao tentar-se alterar os dados da categoria, tente novamente!',
                    [],
                    false,
                    200
                );
            }

            return Response::response(
                'Os dados da categoria foram alterados com sucesso!',
                $requisicao->all(),
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                $requisicao->all(),
                true,
                'Editar categoria de produto'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se alterar os dados da categoria, tente novamente!',
                [],
                false,
                200
            );
        }

    }
}
