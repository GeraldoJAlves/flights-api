<?php

use App\Services\FlightsRetrieverService;

class FlightsGroupControllerTest extends TestCase
{

    private function mockFlightsRetrieverService($flights, $groups)
    {
        $service = Mockery::mock(FlightsRetrieverService::class);
        $service->shouldReceive('getFlights')->once()->with('')->andReturn($flights);
        $service->shouldReceive('getFlightsGroups')->once()->andReturn($groups);

        $this->app->instance(FlightsRetrieverService::class, $service);
    }

    public function testWhenNotFoundRoute()
    {
        $this->json('GET', '/api')
            ->seeJsonEquals(['error' => [
                'code' => 404, 'message' => 'Not Found'
            ]]);
    }

    public function testWhenErrorServer()
    {
        $badRequest = $this->getFile('badRequest.json');
        $service = Mockery::mock(FlightsRetrieverService::class);
        $service->shouldReceive('getFlights')->once()->with('')->andReturn(new Exception());
        $service->shouldReceive('getFlightsGroups')->once()->andReturn([]);

        $this->app->instance(FlightsRetrieverService::class, $service);

        $this->json('GET', '/api/flights/groups')
            ->seeJsonEquals($badRequest);
    }

    public function testWhenNotFoundFlights()
    {
        $this->mockFlightsRetrieverService([], []);

        $this->json('GET', '/api/flights/groups')
            ->seeJson([
                'cheapestGroup' => 0,
                'cheapestPrice' => 0,
                'flights' => [],
                'groups' => [],
                'totalFlights' => 0,
                'totalGroups' => 0,
            ]);
    }

    public function testWhenFoundFlights()
    {
        $flights = ['flight'];
        $groups = [];

        $this->mockFlightsRetrieverService($flights, $groups);

        $this->json('GET', '/api/flights/groups')
            ->seeJson([
                'cheapestGroup' => 0,
                'cheapestPrice' => 0,
                'flights' => ['flight'],
                'groups' => [],
                'totalFlights' => 1,
                'totalGroups' => 0,
            ]);
    }

    public function testWhenFoundGroups()
    {
        $flights = [];
        $groups = [[
            'uniqueId'=> 2,
            'totalPrice' => 20.2
        ]];

        $this->mockFlightsRetrieverService($flights, $groups);

        $this->json('GET', '/api/flights/groups')
            ->seeJson([
                'cheapestGroup' => 2,
                'cheapestPrice' => 20.2,
                'flights' => [],
                'groups' => [[
                    'uniqueId'=> 2,
                    'totalPrice' => 20.2
                ]],
                'totalFlights' => 0,
                'totalGroups' => 1,
            ]);
    }
}
