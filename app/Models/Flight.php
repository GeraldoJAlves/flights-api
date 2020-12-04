<?php

namespace App\Models;

use Jenssegers\Model\Model;

class Flight extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'cia',
        'fare',
        'flightNumber',
        'origin',
        'destination',
        'departureDate',
        'arrivalDate',
        'departureTime',
        'arrivalTime',
        'classService',
        'price',
        'tax',
        'outbound',
        'inbound',
        'duration',
    ];

    public function isValid()
    {
        $keys = array_keys($this->attributes);
        if (
            !isset($this->attributes['id']) ||
            !isset($this->attributes['fare']) ||
            !isset($this->attributes['price'])
        ) {
            return false;
        }

        return $this->attributes['id'] && $this->attributes['fare'] && $this->attributes['price'];
    }
}
