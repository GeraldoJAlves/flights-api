<?php

$router->get('/flights/groups', 'FlightGroupController@list');

$router->get('/flights','FlightController@redirect');

$router->get('/flights/{id:[0-9]+}','FlightController@redirectToID');
