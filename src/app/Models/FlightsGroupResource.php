<?php

namespace App\Models;

use Jenssegers\Model\Model;

class FlightsGroupResource extends Model
{

  protected $attributes = [
    'flights' => [],
    'groups' => [],
    'totalGroups' => 0,
    'totalFlights' => 0,
    'cheapestGroup' => 0,
    'cheapestPrice' => 0,
  ];

  public function setFlights(array $flights)
  {
    $this->attributes['flights'] = $flights;
    $this->attributes['totalFlights'] = count($flights);
  }

  public function setGroups(array $groups)
  {
    $this->attributes['groups'] = $groups;
    $this->attributes['totalGroups'] = count($groups);
    $this->attributes['cheapestGroup'] = 0;
    $this->attributes['cheapestPrice'] = 0;
    if (count($groups)) {
      $this->attributes['cheapestGroup'] = $groups[0]['uniqueId'];
      $this->attributes['cheapestPrice'] = $groups[0]['totalPrice'];
    }
  }
}
