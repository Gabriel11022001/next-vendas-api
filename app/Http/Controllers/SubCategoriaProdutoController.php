<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Servico\SubCategoriaProdutoServico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubCategoriaProdutoController extends Controller
{
    private SubCategoriaProdutoServico $subCategoriaProdutoServico;

    public function __construct(SubCategoriaProdutoServico $subCategoriaProdutoServico)
    {
        $this->subCategoriaProdutoServico = $subCategoriaProdutoServico;
    }

    public function cadastrarSubCategoriaProduto(Request $requisicao): JsonResponse
    {

        return $this->subCategoriaProdutoServico->salvar($requisicao);
    }

    public function listarTodasSubCategorias(): JsonResponse
    {

        return $this->subCategoriaProdutoServico->listarTodos();
    }

    public function buscarSubCategoriaPeloId(string $idHash): JsonResponse
    {

        return $this->subCategoriaProdutoServico->buscarSubCategoriaProdutoPeloId($idHash);
    }
}
