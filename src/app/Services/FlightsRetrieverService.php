<?php

namespace App\Services;

use App\Models\Flight;
use App\Models\FlightGroup;
use App\Models\FlightWrapper;
use GuzzleHttp\Client;

class FlightsRetrieverService
{
    private $client = null;
    private $flights = null;
    private $filter = '';
    private $filterChanged = false;
    private $baseUri = '';

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->baseUri = env('API_FLIGHTS');
    }

    private function requestFlights()
    {
        if (!$this->filterChanged && !empty($this->flights)) {
            return;
        }
        $response = $this->client->request('GET', $this->baseUri . 'flights' . $this->filter);
        // var_dump(get_class($response));die;
        $contentBody = $response->getBody()->getContents();
        $flightsData = json_decode($contentBody, true);
        $this->flights = array_map(function ($flightData) {
            return new Flight($flightData);
        }, $flightsData);
    }

    private function setFilter($filter)
    {
        if (!empty($filter)) {
            $this->filter = substr($filter, 0, 1) === '?' ? $filter : '?' . $filter;
            $this->filterChanged = true;
        }
    }

    public function getFlights($filter = '')
    {
        $this->setFilter($filter);
        $this->requestFlights();
        return $this->flights;
    }


    private function makeFlightsWrappers(array $flights)
    {
        $inboundList = [];
        $outboundList = [];
        while (count($flights)) {
            $flightType = new FlightWrapper();
            foreach ($flights as $ind => $flight) {
                if ($flightType->addFlight($flight)) {
                    unset($flights[$ind]);
                }
            }
            if ($flightType->inbound) {
                $inboundList[] = $flightType;
            } else {
                $outboundList[] = $flightType;
            }
        }
        return [
            'inboound' => $inboundList,
            'outbound' => $outboundList,
        ];
    }

    private function makePossibleGroups(array $wrappers)
    {
        $flightsGroupList = [];
        $outboundList = $wrappers['outbound'];
        $inboundList = $wrappers['inboound'];
        foreach ($outboundList as $flightOutbound) {
            foreach ($inboundList as $flightInbound) {
                $flightGroup = new FlightGroup();
                if ($flightGroup->addFlights($flightOutbound) && $flightGroup->addFlights($flightInbound)) {
                    $flightsGroupList[] = $flightGroup;
                }
            }
        }
        return $flightsGroupList;
    }

    public function getFlightsGroups()
    {
        $this->requestFlights();

        // Montando os wrappers de inbound e outbound
        $wrappers = $this->makeFlightsWrappers($this->flights);

        // Combinacoes possiveis entre outbound e inbound
        $flightsGroupList = $this->makePossibleGroups($wrappers);

        $flightsGroupList = collect($flightsGroupList)->sortBy('totalPrice')->toArray();

        return array_values($flightsGroupList);
    }
}
