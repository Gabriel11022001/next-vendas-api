<?php

namespace App\Repositorio;

use App\DTO\TabelaPrecoDTO;
use App\Models\TabelaPreco;

class TabelaPrecoRepositorio
{

    public function buscarPeloId(string $idHash): TabelaPrecoDTO|null
    {
        $tabelaPreco = TabelaPreco::where('tabela_preco_id_hash', $idHash)
            ->first();

        if (!$tabelaPreco) {

            return null;
        }

        $tabelaPrecoDTO = new TabelaPrecoDTO();
        $tabelaPrecoDTO->id = $tabelaPreco->id;
        $tabelaPrecoDTO->tabelaPrecoIdHash = $tabelaPreco->tabela_preco_id_hash;
        $tabelaPrecoDTO->titulo = $tabelaPreco->titulo;
        $tabelaPrecoDTO->status = $tabelaPreco->ativo;

        return $tabelaPrecoDTO;
    }
}
