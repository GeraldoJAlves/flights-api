<?php

namespace App\Http\Controllers;

use App\Services\FlightsRetriever;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class FlightGroupController extends BaseController
{

    private $flightsRetrivier = null;

    public function __construct(FlightsRetriever $flightsRetrivier)
    {
        $this->flightsRetrivier = $flightsRetrivier;
    }

    public function list(Request $request)
    {
        $filter = $request->getQueryString();
        $flightsList = $this->flightsRetrivier->getFlights($filter);
        $groupsList = $this->flightsRetrivier->getFlightsGroups($flightsList);
        $cheapestGroup = count($groupsList) ? $groupsList[0] : null;
        return new JsonResponse(array(
            'flights' => $flightsList,
            'groups' => $groupsList,
            'totalGroups' => count($groupsList),
            'totalFlights' => count($flightsList),
            'cheapestPrice' => $cheapestGroup ? $cheapestGroup->totalPrice : 0,
            'cheapestGroup' => $cheapestGroup ? $cheapestGroup->uniqueId : 0,
        ), 200);
    }

    public function flights(Request $request)
    {
        $filter = $request->getQueryString();
        $flightsList = $this->flightsRetrivier->getFlights($filter);
        return $flightsList;
    }
}
