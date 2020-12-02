<?php

namespace App\Models;

use Jenssegers\Model\Model;

class Flight extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        // 'cia',
        'fare',
        // 'flightNumber',
        // 'origin',
        // 'destination',
        // 'departureDate',
        // 'arrivalDate',
        // 'departureTime',
        // 'arrivalTime',
        // 'classService',
        'price',
        // 'tax',
        'outbound',
        'inbound',
        // 'duration',
    ];
}