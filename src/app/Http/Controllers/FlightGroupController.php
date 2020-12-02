<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\FlightGroup;
use App\Models\FlightType;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Laravel\Lumen\Routing\Controller as BaseController;

class FlightGroupController extends BaseController
{

    private $flightRequest = null;

    public function __construct()
    {
        $this->flightRequest = $this->createRequest();
    }

    private function createRequest()
    {
        return new Client([
            'base_uri' => 'http://prova.123milhas.net/api/',
        ]);
    }

    private function getFlights($filter = '')
    {
        if (!empty($filter)) {
            $filter = substr($filter, 0, 1) === '?' ? $filter : '?' . $filter;
        }
        $response = $this->flightRequest->request('GET', 'flights' . $filter);
        if ($response->getStatusCode() !== 200) {
            throw new Exception('Error get flights');
        }
        $contentBody = $response->getBody()->getContents();

        $flightsList = array_map(function ($flight) {
            return new Flight($flight);
        }, json_decode($contentBody, true));

        return $flightsList;
    }

    private function createFlightsGroup(array $flightsList)
    {

        // Montando os grupos de inbound e outbound
        $flightsInboundList = [];
        $flightsOutboundList = [];
        while (count($flightsList)) {
            $flightType = new FlightType();
            foreach ($flightsList as $ind => $flight) {
                if ($flightType->addFlight($flight)) {
                    unset($flightsList[$ind]);
                }
            }
            if ($flightType->inbound) {
                $flightsInboundList[] = $flightType;
            } else {
                $flightsOutboundList[] = $flightType;
            }
        }

        // Criando as combinacoes possiveis entre inbound e outbound
        $flightsGroupList = [];
        foreach ($flightsOutboundList as $flightOutbound) {
            foreach ($flightsInboundList as $flightInbound) {
                $flightGroup = new FlightGroup();
                if( $flightGroup->addFlights($flightOutbound) && $flightGroup->addFlights($flightInbound)) {
                    $flightsGroupList[] = $flightGroup;
                }
            }
        }

        $flightsGroupList = collect($flightsGroupList)->sortBy('totalPrice');

        return $flightsGroupList;
    }

    public function list(Request $request)
    {
        // try {
            $filter = $request->getQueryString();
            $flightsList = $this->getFlights($filter);
            $groupsList = $this->createFlightsGroup($flightsList);
            $cheapestGroup = count($groupsList) ? $groupsList[0] : null;
            return array(
                'flights' => $flightsList,
                'groups' => $groupsList,
                'totalGroups' => count($groupsList),
                'totalFlights' => count($flightsList),
                'cheapestPrice' => $cheapestGroup ? $cheapestGroup->totalPrice : 0,
                'cheapestGroup' => $cheapestGroup ? $cheapestGroup->uniqueId : 0,
            );
        // } catch (Exception $exception) {
        //     return new Response('Server error ocurred', 500);
        // }
    }

    public function flights(Request $request)
    {
        try {
            $filter = $request->getQueryString();
            $flightsList = $this->getFlights($filter);
            return $flightsList;
        } catch (Exception $exception) {
            return new Response('Server error ocurred', 500);
        }
    }
}
