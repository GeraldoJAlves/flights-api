<?php

namespace App\Http\Controllers;

use App\Services\FlightsRetrieverService;
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
            $cheapestGroup = count($groupsList) ? $groupsList[0] : null;
            return $this->httpHelper->ok(array(
                'flights' => $flightsList,
                'groups' => $groupsList,
                'totalGroups' => count($groupsList),
                'totalFlights' => count($flightsList),
                'cheapestPrice' => $cheapestGroup ? $cheapestGroup->totalPrice : 0,
                'cheapestGroup' => $cheapestGroup ? $cheapestGroup->uniqueId : 0,
            ));
        } catch (Exception $e) {
            return $this->httpHelper->badRequest();
        }
    }

    public function flights(Request $request)
    {
        $filter = $request->getQueryString();
        $flightsList = $this->flightsRetrieverService->getFlights($filter);
        return $flightsList;
    }
}
