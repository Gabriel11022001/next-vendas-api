<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Servico\TabelaPrecoServico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TabelaPrecoController extends Controller
{
    private TabelaPrecoServico $tabelaPrecoServico;

    public function __construct(TabelaPrecoServico $tabelaPrecoServico)
    {
        $this->tabelaPrecoServico = $tabelaPrecoServico;
    }

    public function cadastrarTabelaPreco(Request $requisicao): JsonResponse
    {

        return $this->tabelaPrecoServico->salvar($requisicao);
    }

    public function listarTodos(): JsonResponse
    {

        return $this->tabelaPrecoServico->listarTodos();
    }

    public function buscarTabelaPrecoPeloId(string $idHash): JsonResponse
    {

        return $this->tabelaPrecoServico->buscarTabelaPrecoPeloId($idHash);
    }

    public function alterarStatusTabelaPreco(string $idHash): JsonResponse
    {

        return $this->tabelaPrecoServico->alterarStatusTabelaPreco($idHash);
    }
}
