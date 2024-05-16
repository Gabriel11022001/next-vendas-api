<?php

namespace App\Http\Servico;

use App\DTO\ProdutoDTO;
use App\Models\CategoriaProduto;
use App\Models\Produto;
use App\Models\SubCategoriaProduto;
use App\Models\UnidadeMedida;
use App\Repositorio\CategoriaRepositorio;
use App\Repositorio\ProdutoRepositorio;
use App\Repositorio\TabelaPrecoRepositorio;
use App\Repositorio\UnidadeMedidaRepositorio;
use App\Utils\Log;
use App\Utils\Response;
use App\Utils\ValidaDadosCadastroProduto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProdutoServico implements IServicoProduto
{

    private ProdutoRepositorio $produtoRepositorio;
    private TabelaPrecoRepositorio $tabelasPrecoRepositorio;
    private CategoriaRepositorio $categoriaRepositorio;
    private UnidadeMedidaRepositorio $unidadeMedidaRepositorio;

    public function __construct()
    {
        $this->produtoRepositorio = new ProdutoRepositorio();
        $this->tabelasPrecoRepositorio = new TabelaPrecoRepositorio();
        $this->categoriaRepositorio = new CategoriaRepositorio();
        $this->unidadeMedidaRepositorio = new UnidadeMedidaRepositorio();
    }

    public function salvar(Request $requisicao): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validador = ValidaDadosCadastroProduto::validar($requisicao);

            if (is_array($validador)) {

                return Response::response(
                    'Ocorreram erros de validação de dados!',
                    $validador,
                    false,
                    200
                );
            }

            $tabelasPreco = $requisicao->tabelas_preco;

            if (!$this->validarTabelasPrecoExistem($tabelasPreco)) {

                return Response::response(
                    'Tabela(s) de preço inválida(s)!',
                    [],
                    false,
                    200
                );
            }

            if (!$this->validarTabelasPrecoEstaoAtivas($tabelasPreco)) {

                return Response::response(
                    'As tabelas de preço não estão ativas!',
                    [],
                    false,
                    200
                );
            }

            if (!$this->validarPrecos($tabelasPreco)) {

                return Response::response(
                    'Preço(s) inválido(s)',
                    [],
                    false,
                    200
                );
            }

            $categoria = $this->categoriaRepositorio->buscarPeloId($requisicao->categoria_produto_id_hash);

            if (!$categoria) {

                return Response::response(
                    'Não existe uma categoria cadastrada no banco de dados com esse id!',
                    [],
                    false,
                    200
                );
            }

            if (!$categoria->status) {

                return Response::response(
                    'A categoria em questão não está ativa!',
                    [],
                    false,
                    200
                );
            }

            $unidadeMedida = $this->unidadeMedidaRepositorio->buscarPeloId($requisicao->unidade_medida_id_hash);

            if (!$unidadeMedida) {

                return Response::response(
                    'Não existe uma unidade de medida cadastrada no banco de dados com esse id!',
                    [],
                    false,
                    200
                );
            }

            if (isset($requisicao->estoque_minimo) && isset($requisicao->estoque_maximo)) {

                if ($requisicao->estoque_minimo > $requisicao->estoque_maximo) {

                    return Response::response(
                        'O estoque mínimo não pode ser maior que o estoque máximo!',
                        [],
                        false,
                        200
                    );
                }

            }

            if (isset($requisicao->unidades_estoque) && isset($requisicao->estoque_maximo)) {

                if ($requisicao->unidades_estoque > $requisicao->estoque_maximo) {

                    return Response::response(
                        'Quantidade de unidades em estoque inválida!',
                        [],
                        false,
                        200
                    );
                }

            }

            if (isset($requisicao->unidades_estoque) && isset($requisicao->estoque_minimo)) {

                if ($requisicao->unidades_estoque < $requisicao->estoque_minimo) {

                    return Response::response(
                        'Quantidade de unidades em estoque inválida!',
                        [],
                        false,
                        200
                    );
                }

            }

            $produtoDTO = new ProdutoDTO($requisicao->all());
            $produtoDTOCadastrado = $this->produtoRepositorio->cadastrar($produtoDTO);

            if (!$produtoDTOCadastrado) {

                return Response::response(
                    'Ocorreu um erro ao tentar-se cadastrar o produto!',
                    [],
                    false,
                    200
                );
            }

            $produtoDTOCadastrado->idProdutoHash = md5($produtoDTOCadastrado->id);

            if (!$this->produtoRepositorio->atualizarIdHash($produtoDTOCadastrado->idProdutoHash, $produtoDTOCadastrado->id)) {
                DB::rollBack();

                return Response::response(
                    'Ocorreu um erro ao tentar-se cadastrar o produto!',
                    [],
                    false,
                    200
                );
            }

            if (!$this->produtoRepositorio->vincularProdutoTabelasPreco($produtoDTOCadastrado)) {
                DB::rollBack();

                return Response::response(
                    'Ocorreu um erro ao tentar-se cadastrar o produto!',
                    [],
                    false,
                    200
                );
            }

            DB::commit();
            $produtoDTOCadastrado->status = true;
            $produtoDTOCadastrado->promocao = false;

            return Response::response(
                'Produto cadastrado com sucesso!',
                $produtoDTOCadastrado->converterArray(),
                true,
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::registrar(
                $e->getMessage(),
                $requisicao->all(),
                true,
                'Cadastrar produto'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se cadastrar o produto!',
                [],
                false,
                200
            );
        }

    }

    private function validarTabelasPrecoExistem(array $tabelasPreco): bool
    {
        $tabelasPrecoExistem = true;

        foreach ($tabelasPreco as $tabela) {
            $tabelaPrecoDTO = $this->tabelasPrecoRepositorio->buscarPeloId($tabela['id_hash']);

            if (!$tabelaPrecoDTO) {
                $tabelasPrecoExistem = false;
            }

        }

        return $tabelasPrecoExistem;
    }

    private function validarTabelasPrecoEstaoAtivas(array $tabelasPreco): bool
    {
        $ativas = true;

        foreach ($tabelasPreco as $tabela) {
            $tabelaDTO = $this->tabelasPrecoRepositorio->buscarPeloId($tabela['id_hash']);

            if (!$tabelaDTO->status) {
                $ativas = false;
            }

        }

        return $ativas;
    }

    private function validarPrecos(array $tabelasPreco): bool
    {
        $precosValidos = true;

        foreach ($tabelasPreco as $tabela) {

            if ($tabela['preco'] < 0) {
                $precosValidos = false;
            }

        }

        return $precosValidos;
    }

    public function editarProduto(Request $requisicao): JsonResponse
    {

    }

    public function listarTodos(): JsonResponse
    {

        try {
            $produtos = $this->produtoRepositorio->listarTodos();

            if (count($produtos) === 0) {

                return Response::response(
                    'Não existem produtos cadastrados no banco de dados!',
                    [],
                    true,
                    200
                );
            }

            return Response::response(
                'Produtos encontrados com sucesso!',
                $produtos,
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar($e->getMessage(), [], true, 'Listar todos os produtos');

            return Response::response(
                'Ocorreu um erro ao tentar-se listar os produtos cadastrados no banco de dados!',
                [],
                false,
                200
            );
        }

    }

    public function buscarProdutoPeloId(string $idHash): JsonResponse
    {

    }

    public function buscarProdutosAbaixoEstoqueMinimo(): JsonResponse
    {

    }

    public function buscarProdutosAcimaEstoqueMaximo(): JsonResponse
    {

    }

    public function buscarProdutosAtivosOuInativos(bool $ativo): JsonResponse
    {

    }

    public function buscarProdutosPelaCategoria(string $idHashCategoria): JsonResponse
    {

    }

    public function buscarProdutosPelaSubCategoria(string $idHashSubCategoria): JsonResponse
    {

    }

    public function buscarProdutosPelaTabelaPreco(string $idHashTabelaPreco): JsonResponse
    {

    }

    public function alterarStatusProduto(string $idHash): JsonResponse
    {

        try {

            if ($this->produtoRepositorio->alterarStatus($idHash)) {

                return Response::response(
                    'Status alterado com sucesso!',
                    [],
                    true,
                    200
                );
            }

            return Response::response(
                'Ocorreu um erro ao tentar-se alterar o status do produto!',
                [],
                false,
                200
            );
        } catch (Exception $e) {
            Log::registrar(
                $e->getMessage(),
                [
                    'id_hash_produto' => $idHash
                ],
                false,
                'Alterar status do produto'
            );

            return Response::response(
                'Ocorreu um erro ao tentar-se alterar o status do produto!',
                [],
                false,
                200
            );
        }

    }

    public function controlarQuantidadeUnidadesProdutoEstoque(Request $requisicao): JsonResponse
    {

    }

    public function filtrarProdutos(Request $requisicao): JsonResponse
    {

    }

    public function buscarProdutoRelacionamentoTabelaPreco(int $idRelacionamentoProdutoTabelaPreco): JsonResponse
    {

        try {
            $produto = $this->produtoRepositorio->buscarPeloIdRelacionamentoProdutoTabelaPreco($idRelacionamentoProdutoTabelaPreco);

            if (!$produto) {

                return Response::response(
                    'O produto não foi encontrado na base de dados!',
                    [],
                    true,
                    200
                );
            }

            return Response::response(
                'Produto encontrado com sucesso!',
                $produto,
                true,
                200
            );
        } catch (Exception $e) {
            Log::registrar($e->getMessage(), [ 'id_relacionamento_produto_tabela_preco' => $idRelacionamentoProdutoTabelaPreco ], true, 'Buscar produto');

            return Response::response(
                'Ocorreu um erro ao tentar-se consultar o produto!',
                [],
                false,
                200
            );
        }

    }
}
