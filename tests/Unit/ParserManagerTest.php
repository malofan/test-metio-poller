<?php

namespace Tests\Unit;

use App\Sensors\Parsers\ParserManager;
use App\Sensors\Parsers\UnparseableInputException;
use App\Sensors\SensorDataDTO;
use App\Sensors\SensorDataDTOList;
use Tests\TestCase;

class ParserManagerTest extends TestCase
{
    /**
     * @var ParserManager
     */
    protected $parser;

    protected $jsonString = '{
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

    protected $csvString = <<<EOT
"19.04.2018","Kiev",22,10,58,66
"19.04.2018","Zhitomir",23,11,60,70
"19.04.2018","Odessa",30,17,55,77'
EOT;

    protected function setUp()
    {
        parent::setUp();
        $this->parser = new ParserManager();
    }

    public function testCSVIsParsed()
    {
        $this->assertInstanceOf(SensorDataDTO::class, $this->parser->parse($this->csvString)->current());
    }

    public function testJSONIsParsed()
    {
        $this->assertInstanceOf(SensorDataDTO::class, $this->parser->parse($this->jsonString)->current());
    }

    public function testThrowsUnparseableException()
    {
        try {
            $this->parser->parse('');
        }
        catch (UnparseableInputException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->assertTrue(false);
    }
}
