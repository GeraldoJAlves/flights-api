<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

class HttpHelper
{

    private function createReponseError($data, $code)
    {
        return new JsonResponse(['error' => [
            'code' =>  $code,
            'message' => $data,
        ]], $code);
    }

    public function notFound()
    {
        return $this->createReponseError('No route found', 404);
    }

    public function badRequest()
    {
        return $this->createReponseError('Server error', 500);
    }

    public static function ok($data)
    {
        return new JsonResponse($data, 200);
    }
}
