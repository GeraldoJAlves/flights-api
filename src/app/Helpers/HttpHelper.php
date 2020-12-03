<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

class HttpHelper
{

    public static function notFound()
    {
        return new JsonResponse(['message' => 'No route found'], 404);
    }

    public static function badRequest()
    {
        return new JsonResponse(['message' => 'No route found'], 500);
    }

    public static function ok($data)
    {
        return new JsonResponse($data, 200);
    }
}
