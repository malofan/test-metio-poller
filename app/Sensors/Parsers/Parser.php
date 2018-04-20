<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDataDTOList;

interface Parser
{
    /**
     * @param string $input
     * @return SensorDataDTOList
     */
    public function parse(string $input): SensorDataDTOList;
}