<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log as FacadesLog;

class Log
{

    public static function registrar(
        string $mensagem = '',
        array $dadosRequisicao = [],
        bool $erro = true,
        string $processo
    ): void
    {
        $processo = mb_strtoupper($processo);

        if ($erro) {
            FacadesLog::error($processo . ' - ' . $mensagem, $dadosRequisicao);
        } else {
            FacadesLog::debug($processo, $dadosRequisicao);
        }

    }
}
