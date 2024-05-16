<?php

namespace App\Repositorio;

use App\DTO\UnidadeMedidaDTO;
use App\Models\UnidadeMedida;

class UnidadeMedidaRepositorio
{

    public function buscarPeloId(string $idHash): UnidadeMedidaDTO|null
    {
        $unidadeMedida = UnidadeMedida::where('unidade_medida_id_hash', $idHash)
            ->first();

        if (!$unidadeMedida) {

            return null;
        }

        $unidadeMedidaDTO = new UnidadeMedidaDTO();
        $unidadeMedidaDTO->unidadeMedidaIdHash = $unidadeMedida->unidade_medida_id_hash;
        $unidadeMedidaDTO->descricao = $unidadeMedida->descricao;

        return $unidadeMedidaDTO;
    }

    public function listarTodas(): array
    {
        $unidadesMedidas = UnidadeMedida::paginate(10)
            ->select([
                'unidade_medida_id_hash',
                'descricao'
            ])
            ->toArray();

        if (count($unidadesMedidas) === 0) {

            return [];
        }

        $unidadesMedidaDTO = [];

        foreach ($unidadesMedidas as $unidade) {
            $unidadeMedidaDTO = new UnidadeMedidaDTO();
            $unidadeMedidaDTO->unidadeMedidaIdHash = $unidade->unidade_medida_id_hash;
            $unidadeMedidaDTO->descricao = $unidade->descricao;
            $unidadesMedidaDTO[] = $unidadeMedidaDTO;
        }

        return $unidadesMedidaDTO;
    }
}
