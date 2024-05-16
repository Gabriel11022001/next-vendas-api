<?php

namespace App\Repositorio;

use App\DTO\ProdutoDTO;
use App\Models\CategoriaProduto;
use App\Models\Produto;
use App\Models\ProdutoTabelaPreco;
use App\Models\SubCategoriaProduto;
use App\Models\TabelaPreco;
use App\Models\UnidadeMedida;
use Illuminate\Support\Facades\DB;

class ProdutoRepositorio
{

    public function cadastrar(ProdutoDTO $produtoDTO): ProdutoDTO|null
    {
        $produto = new Produto();
        $produto->nome = $produtoDTO->nome;
        $produto->descricao = $produtoDTO->descricao;
        $produto->preco_custo = $produtoDTO->precoCusto;
        $produto->codigo_barras = $produtoDTO->codigoBarras;
        $produto->percentual_desconto_em_promocao = $produtoDTO->percentualDescontoPromocao;
        $produto->data_cadastro = $produtoDTO->dataCadastro->format('Y-m-d H:i:s');
        $categoria = CategoriaProduto::where('categoria_id_hash', $produtoDTO->categoriaProdutoId)
            ->first();

        if (!empty($produtoDTO->subCategoriaProdutoId)) {
            $subCategoria = SubCategoriaProduto::where('sub_categoria_id_hash', $produtoDTO->subCategoriaProdutoId)
                ->first();
            $produto->sub_categoria_produto_id = $subCategoria->id;
        }

        $unidadeMedida = UnidadeMedida::where('unidade_medida_id_hash', $produtoDTO->unidadeMedidaId)
            ->first();

        $produto->categoria_produto_id = $categoria->id;
        $produto->unidade_medida_id = $unidadeMedida->id;
        $produto->unidades_estoque = $produtoDTO->quantidadeUnidadesEstoque;
        $produto->estoque_maximo = $produtoDTO->estoqueMaximo;
        $produto->estoque_minimo = $produtoDTO->estoqueMinimo;

        if ($produto->save()) {
            $produtoDTO->id = $produto->id;

            return $produtoDTO;
        } else {

            return null;
        }

    }

    public function atualizarIdHash(string $idHash, int $idProduto): bool
    {
        $produto = Produto::find($idProduto);
        $produto->produto_id_hash = $idHash;

        return $produto->save();
    }

    public function editar(ProdutoDTO $produtoDTO): ProdutoDTO|null
    {

        return null;
    }

    public function listarTodos(): array
    {
        $produtos = Produto::join('produto_tabela_preco', 'produto_tabela_preco.produto_id', '=', 'produtos.id')
            ->join('tabela_precos', 'tabela_precos.id', '=', 'produto_tabela_preco.tabela_preco_id')
            ->select([
                'produtos.produto_id_hash AS id_produto_hash',
                'tabela_precos.tabela_preco_id_hash AS id_tabela_preco_hash',
                'preco_produto',
                'produtos.nome AS nome_produto',
                'produtos.status AS status_produto',
                'tabela_precos.titulo AS tabela_preco',
                'produto_tabela_preco.id AS id_relacionamento_produto_tabela_preco',
                'produtos.unidades_estoque'
            ])
            ->paginate(5)
            ->toArray();

        if (count($produtos) === 0) {

            return [];
        }

        return $produtos;
    }

    public function buscarPeloIdRelacionamentoProdutoTabelaPreco(int $produtoTabelaPrecoId): array
    {
        $produto = Produto::join('produto_tabela_preco', 'produto_tabela_preco.produto_id', '=', 'produtos.id')
            ->join('tabela_precos', 'tabela_precos.id', '=', 'produto_tabela_preco.id')
            ->join('unidade_medidas', 'unidade_medidas.id', '=', 'produtos.unidade_medida_id')
            ->join('categoria_produtos', 'categoria_produtos.id', '=', 'produtos.categoria_produto_id')
            ->where('produto_tabela_preco.id', '=', $produtoTabelaPrecoId)
            ->select(
                'produtos.produto_id_hash AS id_produto',
                'tabela_precos.titulo AS tabela_preco',
                'produto_tabela_preco.preco_produto',
                'produtos.nome AS nome_produto',
                'produtos.descricao AS descricao_produto',
                'produtos.status AS status_produto',
                'produtos.data_cadastro AS data_cadastro_produto',
                'codigo_barras',
                'preco_custo',
                'em_promocao',
                'percentual_desconto_em_promocao',
                'categoria_produtos.descricao AS categoria',
                'sub_categoria_produto_id',
                'produtos.unidades_estoque',
                'produtos.estoque_maximo',
                'produtos.estoque_minimo'
            )
            ->get()
            ->toArray();

        if (!$produto) {

            return [];
        }

        if (!$produto[0]['sub_categoria_produto_id']) {
            $produto[0]['sub_categoria'] = null;
        } else {
            $subCategoria = SubCategoriaProduto::where('id', $produto[0]['sub_categoria_produto_id'])
            ->first()
            ->toArray();
            $produto[0]['sub_categoria'] = $subCategoria['descricao'];
        }

        return $produto[0];
    }

    public function vincularProdutoTabelasPreco(ProdutoDTO $produtoDTO): bool
    {
        $vinculoRealizadoSucesso = true;

        foreach ($produtoDTO->tabelasPreco as $tabelaPrecoProduto) {
            $tabelaPrecoProdutoCadastrar = new ProdutoTabelaPreco();
            $tabelaPrecoProdutoCadastrar->produto_id = $produtoDTO->id;
            $tabelaPreco = TabelaPreco::where('tabela_preco_id_hash', $tabelaPrecoProduto['id_hash'])
                ->first();
            $tabelaPrecoProdutoCadastrar->preco_produto = $tabelaPrecoProduto['preco'];
            $tabelaPrecoProdutoCadastrar->tabela_preco_id = $tabelaPreco->id;

            if (!$tabelaPrecoProdutoCadastrar->save()) {
                $vinculoRealizadoSucesso = false;
            }

        }

        return $vinculoRealizadoSucesso;
    }

    public function buscarAcimaEstoqueMaximo(): array
    {

    }

    public function buscarAbaixoEstoqueMinimo(): array
    {

    }

    public function buscarAtivos(): array
    {

    }

    public function buscarInativos(): array
    {

    }

    public function buscarPelaCategoria(string $idHashCategoria): array
    {

    }

    public function buscarPelaSubCategoria(string $idHashSubCategoria): array
    {

    }

    public function buscarPelaTabelaPreco(string $idHashTabelaPreco): array
    {

    }

    public function alterarStatus(string $idHashProduto): bool|null
    {
        $produto = Produto::where('produto_id_hash', $idHashProduto)
            ->first();

        if (!$produto) {

            return null;
        }

        $produto->status = !$produto->status;

        return $produto->save();
    }
}
