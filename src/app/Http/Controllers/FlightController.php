<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class FlightController extends BaseController
{
    public function redirect(Request $request)
    {
        $filter = $request->getQueryString();
        if ($filter) $filter = '?' . $filter;
        return redirect(env('API_FLIGHTS') . 'flights'.$filter);
    }

    public function redirectToID(Request $request, $id)
    {
        $filter = $request->getQueryString();
        if ($filter) $filter = '?' . $filter;
        return redirect(env('API_FLIGHTS') . "flights/${id}${filter}");
    }
}
