<?php

namespace Tests\Unit;

use App\Sensors\Parsers\CSVParser;
use App\Sensors\SensorDataDTO;
use App\Sensors\SensorDataDTOList;
use Tests\TestCase;

class CSVParserTest extends TestCase
{
    /**
     * @return void
     */
    public function testItemOfTypeSensorDataDTO()
    {
        $csvString = <<<EOT
"19.04.2018","Kiev",22,10,58,66
"19.04.2018","Zhitomir",23,11,60,70
"19.04.2018","Odessa",30,17,55,77'
EOT;

        $this->assertInstanceOf(SensorDataDTO::class, (new CSVParser(new SensorDataDTOList))->parse($csvString)->current());
    }
}
