<?php

namespace App\Repositorio;

use App\DTO\CategoriaDTO;
use App\Models\CategoriaProduto;

class CategoriaRepositorio
{

    public function buscarPeloId(string $idHash): CategoriaDTO|null
    {
        $categoria = CategoriaProduto::where('categoria_id_hash', $idHash)
            ->first();

        if (!$categoria) {

            return null;
        }

        $categoriaDTO = new CategoriaDTO();
        $categoriaDTO->id = $categoria->id;
        $categoriaDTO->descricao = $categoria->descricao;
        $categoriaDTO->status = $categoria->ativo;
        $categoriaDTO->categoriaIdHash = $categoria->categoria_id_hash;

        return $categoriaDTO;
    }
}
