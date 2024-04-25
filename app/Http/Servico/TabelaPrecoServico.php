<?php

namespace App\Http\Servico;

use App\Models\TabelaPreco;
use App\Utils\Log;
use App\Utils\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TabelaPrecoServico implements IServicoTabelaPreco
{

    public function salvar(Request $requisicao): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validador = Validator::make($requisicao->all(),
            [
                'titulo' => 'required|max:255|min:3|unique:tabela_precos'
            ],
            [
                'titulo.required' => 'Informe o título da tabela de preço!',
                'titulo.max' => 'O título da tabela de preço deve possuir no máximo 255 caracteres!',
                'titulo.min' => 'O título da tabela de preço deve possuir no mínimo 3 caracteres!',
                'titulo.unique' => 'Já existe uma tabela de preço cadastrada com esse título!'
            ]);

            if ($validador->fails()) {

                return Response::response(
                    'Ocorreram erros de validação de dados!',
                    $validador->errors()->toArray(),
                    false,
                    200
                );
            }

            $tabelaPreco = new TabelaPreco();
            $tabelaPreco->titulo = $requisicao->titulo;
            $tabelaPreco->ativo = true;
            $tabelaPreco->tabela_preco_id_hash = '';

            if (!$tabelaPreco->save()) {

                return Response::response(
                    'Ocorreu um erro ao tentar-se cadastrar a tabela de preço!',
                    [],
                    false,
                    200
                );
            }

            $tabelaPreco->tabela_preco_id_hash = md5($tabelaPreco->id);

            if (!$tabelaPreco->save()) {

                return Response::response(
                    'Ocorreu um erro ao tentar-se cadastrar a tabela de preço!',
                    [],
                    false,
                    200
                );
            }

            DB::commit();

            return Response::response(
                'Tabela de preço cadastrada com sucesso!',
                [
                    'id_hash' => $tabelaPreco->tabela_preco_id_hash,
                    'titulo' => $tabelaPreco->titulo,
                    'ativo' => true
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
                'Cadastrar tabela de preço'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se cadastrar a tabela de preço!',
                [],
                false,
                200
            );
        }

    }

    public function listarTodos(): JsonResponse
    {

        try {
            $tabelasPreco = TabelaPreco::all([
                'tabela_preco_id_hash',
                'titulo',
                'ativo'
            ])
            ->toArray();

            if (count($tabelasPreco) === 0) {

                return Response::response(
                    'Não existem tabelas de preço cadastradas no banco de dados!',
                    [],
                    true,
                    200
                );
            }

            for ($contador = 0; $contador < count($tabelasPreco); $contador++) {
                $tabelasPreco[$contador]['ativo'] = $tabelasPreco[$contador]['ativo'] === 1 ? true : false;
            }

            return Response::response(
                'Tabelas de preço encontradas com sucesso!',
                $tabelasPreco,
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                [],
                true,
                'Listar todas as tabelas de preço'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se listar todas as tabelas de preço!',
                [],
                true,
                200
            );
        }

    }

    public function editarTabelaPreco(Request $requisicao): JsonResponse
    {

    }

    public function buscarTabelaPrecoPeloId(string $idHash): JsonResponse
    {

        try {
            $tabelaPreco = TabelaPreco::where('tabela_preco_id_hash', $idHash)
                ->first();

            if (!$tabelaPreco) {

                return Response::response(
                    'Não existe uma tabela de preço cadastrada no banco de dados com esse id!',
                    [],
                    true,
                    200
                );
            }

            return Response::response(
                'Tabela de preço encontrada com sucesso!',
                $tabelaPreco->select('tabela_preco_id_hash', 'titulo', 'ativo')->get()->toArray(),
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                [
                    'id_hash' => $idHash
                ],
                true,
                'Buscar tabela de preço pelo id'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se consultar a tabela de preço pelo id!',
                [],
                false,
                200
            );
        }

    }

    public function atribuirProdutoTabelaPreco(Request $requisicao): JsonResponse
    {

    }

    public function removerProdutoTabelaPreco(Request $requisicao): JsonResponse
    {

    }

    public function alterarStatusTabelaPreco(string $idHash): JsonResponse
    {

    }
}
