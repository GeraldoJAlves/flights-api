<?php

namespace App\Http\Controllers;

use App\Services\FlightsRetrieverService;
use App\Models\FlightsGroupResource;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Helpers\HttpHelper;
use Exception;

class FlightGroupController extends BaseController
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
            $flightsList = $this->flightsRetrieverService->getFlights($filter);
            $groupsList = $this->flightsRetrieverService->getFlightsGroups();
            $resource = new FlightsGroupResource();
            $resource->setFlights($flightsList);
            $resource->setGroups($groupsList);
            return $resource;
        } catch (Exception $e) {
            return $this->httpHelper->badRequest($e);
        }
    }

    public function flights(Request $request)
    {
        $filter = $request->getQueryString();
        $flightsList = $this->flightsRetrieverService->getFlights($filter);
        return $flightsList;
    }
}
