<?php

namespace App\Models;

use Jenssegers\Model\Model;

class FlightWrapper extends Model
{
    protected $attributes = [
        'fare' => null,
        'inbound' => 0,
        'outbound' => 0,
        'price' => 0,
        'flights' => []
    ];

    private $baseFlight = null;

    public function addFlight(Flight $flight)
    {
        if (empty($this->baseFlight)) {
            $this->setBaseFlight($flight);
            $this->addFlightList($flight);
            return true;
        }

        if (!$this->isValidFlight($flight)) {
            return false;
        }

        $this->addFlightList($flight);
        return true;
    }

    private function setBaseFlight(Flight $flight)
    {
        $this->attributes['fare'] = $flight->fare;
        $this->attributes['inbound'] = $flight->inbound;
        $this->attributes['outbound'] = $flight->outbound;
        $this->attributes['price'] = $flight->price;
        $this->baseFlight = $flight;
    }

    private function addFlightList(Flight $flight)
    {
        $this->attributes['flights'][] = ['id' => $flight->id];
    }

    private function isValidFlight(Flight $flight)
    {
        $baseFlight = $this->baseFlight;
        return $baseFlight->fare === $flight->fare &&
            $baseFlight->price === $flight->price &&
            $baseFlight->outbound === $flight->outbound &&
            $baseFlight->inbound === $flight->inbound;
    }
}
