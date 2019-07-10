<?php

namespace Tests\Unit;

use App\Sensors\Parsers\JSONParser;
use App\Sensors\SensorDataDTO;
use App\Sensors\SensorDataDTOList;
use Tests\TestCase;

class JSONParserTest extends TestCase
{
    /**
     * @return void
     */
    public function testItemOfTypeSensorDataDTO()
    {
        $jsonString = '{
          "2017-08-31": {
            "Kyiv": {
                "day": {
                  "temperature": 22,
                  "humidity": 58
                },
                "night": {
                  "temperature": 10,
                  "humidity": 66
                }
            },
            "Zhytomyr": {
                "day": {
                  "temperature": 23,
                  "humidity": 60
                },
                "night": {
                  "temperature": 11,
                  "humidity": 70
                }
            },
            "Odesa": { 
                "day": {
                  "temperature": 30,
                  "humidity": 55
                },
                "night": {
                  "temperature": 17,
                  "humidity": 77
                }
            }
          }
        }';

        $this->assertInstanceOf(SensorDataDTO::class, (new JSONParser(new SensorDataDTOList))->parse($jsonString)->current());
    }
}
