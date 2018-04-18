<?php

namespace App\Sensors;

use GuzzleHttp\Client as HttpClient;
use App\Sensors\Parsers\Parser;


abstract class Sensor
{
    const REQUEST_TIMEOUT = 10;
    /**
     * @var SensorAttributes
     */
    private $attributes;
    /**
     * @var SensorDTO[]
     */
    private $sensorData = [];
    /**
     * @var Parser
     */
    private $parser;

    public function __construct(SensorAttributes $attributes, Parser $parser)
    {
        $this->attributes = $attributes;
        $this->parser = $parser;
    }

    /**
     * @throws \Exception
     */
    public function poll(): void
    {
        $httpClient = new HttpClient();
        try {
            $rowSensorData = $httpClient->get(
                $this->attributes->getUrl(),
                [
                    'timeout' => self::REQUEST_TIMEOUT
                ]
            );
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new \Exception(sprintf("There is no response from sensor with ID: %d in %d seconds", $this->attributes->getId(), self::REQUEST_TIMEOUT));
        }

        $this->sensorData = $this->parser->parse($rowSensorData);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->sensorData;
    }
}