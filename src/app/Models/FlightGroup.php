<?php

namespace App\Models;

use Jenssegers\Model\Model;

class FlightGroup extends Model
{
    private $fare = null;
    private $priceInbound = 0;
    private $priceOutbound = 0;

    public $incrementing = true;
    protected $primaryKey = 'uniqueId';
    protected $attributes = [
        'uniqueId' => 0,
        'totalPrice' => 0,
        'outbound' => [],
        'inbound' => []
    ];

    public static $uniqueId = 1;

    public function addFlights(FlightType $flightType)
    {
        if (!count($flightType->flights)) {
            return false;
        }

        if (empty($this->fare)) {
            $this->attributes['uniqueId'] = self::$uniqueId++;
            $this->fare = $flightType->fare;
        }

        if (!$this->isValidFare($flightType)) {
            return false;
        }
        $this->setFlights($flightType);
        return true;
    }

    private function setFlights(FlightType $flightType)
    {
        if ($flightType->inbound) {
            $this->priceInbound = $flightType->price;
            $this->attributes['inbound'][] = $flightType->flights;
        } else {
            $this->priceOutbound = $flightType->price;
            $this->attributes['outbound'][] = $flightType->flights;
        }

        $this->updateTotal();
    }

    private function updateTotal()
    {
        $this->attributes['totalPrice'] = $this->priceOutbound + $this->priceInbound;
    }

    private function isValidFare(FlightType $flightType)
    {
        return $this->fare === $flightType->fare;
    }
}
