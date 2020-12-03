<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

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

    public function badRequest($e)
    {
        $message = 'Server error';
        // if (app()->environment('local') && $e->getMessage()) {
            $message = $e->getMessage();
        // }
        return $this->createReponseError($message, 500);
    }

    public static function ok($data)
    {
        return new JsonResponse($data, 200);
    }
}
