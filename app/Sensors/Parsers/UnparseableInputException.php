<?php

namespace App\Sensors\Parsers;


use App\Sensor;

class UnparseableInputException extends \Exception
{
    public function __construct(string $input)
    {
        parent::__construct(sprintf('There is no Parser in system that can parse Input:' . PHP_EOL . '%s', str_limit($input)), 0, null);
    }
}