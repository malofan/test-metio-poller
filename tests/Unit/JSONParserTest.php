<?php

namespace Tests\Unit;

use App\Sensors\Parsers\JSONParser;
use App\Sensors\SensorDTO;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JSONParserTest extends TestCase
{
    /**
     * @return void
     */
    public function testJSONParser()
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

        $this->assertInstanceOf(SensorDTO::class, \current((new JSONParser())->parse($jsonString)));
    }
}
