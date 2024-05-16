<?php

namespace App\DTO;

use DateTime;

class ProdutoDTO
{
    public int $id;
    public string $idProdutoHash;
    public string $nome;
    public string $codigoBarras;
    public string $descricao;
    public bool $status;
    public float $precoCusto;
    public DateTime $dataCadastro;
    public bool $promocao;
    public float $percentualDescontoPromocao;
    public int $quantidadeUnidadesEstoque;
    public int $estoqueMaximo;
    public int $estoqueMinimo;
    public string $categoriaProdutoId;
    public string $subCategoriaProdutoId;
    public string $unidadeMedidaId;
    public array $tabelasPreco;

    public function __construct(array $dados = [])
    {
        $this->mapear($dados);
    }

    private function mapear(array $dados = []): void
    {

        if (count($dados) > 0) {
            // converter os dados do produto passados na requisição para as propriedades do objeto
            $this->nome = $dados['nome'];
            $this->descricao = empty($dados['descricao']) ? '' : $dados['descricao'];
            $this->codigoBarras = empty($dados['codigo_barras']) ? '' : $dados['codigo_barras'];
            $this->precoCusto = $dados['preco_custo'];
            $this->percentualDescontoPromocao = empty($dados['percentual_desconto_em_promocao']) ? 0 : $dados['percentual_desconto_em_promocao'];
            $this->categoriaProdutoId = $dados['categoria_produto_id_hash'];
            $this->subCategoriaProdutoId = empty($dados['sub_categoria_produto_id_hash']) ? '' : $dados['sub_categoria_produto_id_hash'];
            $this->unidadeMedidaId = $dados['unidade_medida_id_hash'];
            $this->dataCadastro = new DateTime('now');
            $this->quantidadeUnidadesEstoque = !empty($dados['unidades_estoque']) ? $dados['unidades_estoque'] : 0;
            $this->estoqueMaximo = !empty($dados['estoque_maximo']) ? $dados['estoque_maximo'] : 0;
            $this->estoqueMinimo = !empty($dados['estoque_minimo']) ? $dados['estoque_minimo'] : 0;

            if (!empty($dados['tabelas_preco'])) {
                $this->tabelasPreco = $dados['tabelas_preco'];
            }

        }

    }

    public function converterArray(): array
    {
        $produto = [];
        $produto['id_hash'] = $this->idProdutoHash;
        $produto['nome'] = $this->nome;
        $produto['descricao'] = $this->descricao;
        $produto['status'] = $this->status;
        $produto['data_cadastro'] = $this->dataCadastro->format('d-m-Y H:i:s');
        $produto['preco_custo'] = $this->precoCusto;
        $produto['em_promocao'] = $this->promocao;
        $produto['percentual_desconto_promocao'] = $this->percentualDescontoPromocao;
        $produto['unidades_estoque'] = $this->quantidadeUnidadesEstoque;
        $produto['estoque_maximo'] = $this->estoqueMaximo;
        $produto['estoque_minimo'] = $this->estoqueMinimo;

        return $produto;
    }
}
