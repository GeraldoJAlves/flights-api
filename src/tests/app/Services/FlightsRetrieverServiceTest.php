<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class FlightsRetrieverServiceTest extends TestCase
{

    public function testWhenReponseGuzzleEmpty()
    {
        $flights = $this->getFile('flights.json');
        $fligthsJson = json_encode([$flights[0]]);
        $response = new Response(200, [], $fligthsJson);
        $mock = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->app->instance(Client::class, $client);

        $this->json('GET', '/api/flights')
            ->seeJson([
                $flights[0]
            ]);
    }
}
