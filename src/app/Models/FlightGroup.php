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

    public function addFlights(FlightWrapper $flightWrapper)
    {
        if (!count($flightWrapper->flights)) {
            return false;
        }

        if (empty($this->fare)) {
            $this->attributes['uniqueId'] = self::$uniqueId++;
            $this->fare = $flightWrapper->fare;
        }

        if (!$this->isValidFare($flightWrapper)) {
            return false;
        }
        $this->setFlights($flightWrapper);
        return true;
    }

    private function setFlights(FlightWrapper $flightWrapper)
    {
        if ($flightWrapper->inbound) {
            $this->priceInbound = $flightWrapper->price;
            $this->attributes['inbound'][] = $flightWrapper->flights;
        } else {
            $this->priceOutbound = $flightWrapper->price;
            $this->attributes['outbound'][] = $flightWrapper->flights;
        }

        $this->updateTotal();
    }

    private function updateTotal()
    {
        $this->attributes['totalPrice'] = $this->priceOutbound + $this->priceInbound;
    }

    private function isValidFare(FlightWrapper $flightWrapper)
    {
        return $this->fare === $flightWrapper->fare;
    }
}
