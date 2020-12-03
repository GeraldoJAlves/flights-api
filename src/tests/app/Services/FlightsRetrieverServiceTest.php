<?php

use App\Models\Flight;
use App\Models\FlightGroup;
use App\Services\FlightsRetrieverService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class FlightsRetrieverServiceTest extends TestCase
{

    private function makeSut($code, $flights = [])
    {
        FlightGroup::resetCount();
        $fligthsJson = json_encode($flights);
        $response = new Response($code, [], $fligthsJson);
        $mock = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        return new FlightsRetrieverService($client);
    }

    private function convertArrayToModel($class, $array)
    {
        return array_map(function ($model) use($class) {
            return new $class($model);
        }, $array);
    }

    public function testWhenReponseGuzzleEmpty()
    {

        $sut = $this->makeSut(200, []);
        $this->assertEquals($sut->getFlights(), []);
    }

    public function testWhenReponseGuzzleReturnDifferentTwoHundred()
    {
        $sut = $this->makeSut(400, []);

        $this->expectException(ClientException::class);
        $sut->getFlights();
    }

    public function testWhenResponseGuzzeReturnFlights()
    {
        $flights = $this->getFile('flights.json');

        $sut = $this->makeSut(200, $flights);

        $list = $this->convertArrayToModel(Flight::class, $flights);

        $this->assertEquals($sut->getFlights(), $list);
    }

    public function testWhenResponseGuzzeNoReturnFlights()
    {
        $res = [['no flight']];

        $sut = $this->makeSut(200, $res);
        $this->assertEquals($sut->getFlights(), []);
    }

    public function testGetGroupFlights()
    {
        $flights = $this->getFile('flights.json');
        $groups = $this->getFile('groups.json');

        $sut = $this->makeSut(200, $flights);
        $resGroups = $sut->getFlightsGroups();
        $this->assertEquals(json_encode($resGroups), json_encode($groups));
    }

    public function testGetGroupFlights1AF()
    {
        $flights = $this->getFile('flights-1AF.json');
        $groups = $this->getFile('groups-1AF.json');

        $sut = $this->makeSut(200, $flights);
        $resGroups = $sut->getFlightsGroups();
        $this->assertEquals(json_encode($resGroups[0]), json_encode($groups[0]));
    }

    public function testGetGroupFlights4DA()
    {
        $flights = $this->getFile('flights-4DA.json');
        $groups = $this->getFile('groups-4DA.json');

        $sut = $this->makeSut(200, $flights);
        $resGroups = $sut->getFlightsGroups();
        $this->assertEquals(json_encode($resGroups[0]), json_encode($groups[0]));
    }
}
