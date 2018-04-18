<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDTO;

interface Parser
{
    /**
     * @param string $input
     * @return SensorDTO[]
     */
    public function parse(string $input): array;
}