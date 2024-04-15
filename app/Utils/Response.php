<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;

class Response
{

    public static function response(
        string $mensagem,
        array $dados,
        bool $ok,
        int $codigoHttp
    ): JsonResponse
    {

        return response()
            ->json([
                'mensagem' => $mensagem,
                'dados' => $dados,
                'ok' => $ok
            ], $codigoHttp);
    }
}
