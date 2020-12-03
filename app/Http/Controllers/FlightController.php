<?php

namespace App\Http\Controllers;

use App\Helpers\HttpHelper;
use App\Services\FlightsRetrieverService;
use Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class FlightController extends BaseController
{

    private $flightsRetrieverService = null;
    private $httpHelper = null;

    public function __construct(FlightsRetrieverService $flightsRetrieverService, HttpHelper $httpHelper)
    {
        $this->flightsRetrieverService = $flightsRetrieverService;
        $this->httpHelper = $httpHelper;
    }
    
    public function list(Request $request)
    {
        try {
            $filter = $request->getQueryString();
            $fligths = $this->flightsRetrieverService->getFlights($filter);
            return $this->httpHelper->ok($fligths);
        } catch(Exception $e) {
            return $this->httpHelper->badRequest($e);
        }
    }

    public function getById(Request $request, $id)
    {
        $filter = $request->getQueryString();
        if ($filter) $filter = '?' . $filter;
        return redirect(env('API_FLIGHTS') . "flights/${id}${filter}");
    }
}
