<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDTO;

class ParserManager
{
    /**
     * The array of resolved Parsers.
     *
     * @var Parser[]
     */
    protected $instances = [];

    protected $parsers = [
        JSONParser::class,
        CSVParser::class,
    ];

    /**
     * Get a Parser instance.
     *
     * @param  string  $input
     * @return SensorDTO[]
     *
     * @throws UnparseableInputException
     */
    public function parse($input): array
    {
        foreach ($this->parsers as $parser) {
            $instance = $this->instances[$parser] ?? $this->instance($parser);
            $output = $instance->parse($input);

            if (!empty($output)) {

                return $output;
            }
        }

        throw new UnparseableInputException($input);
    }

    /**
     * Attempt to get one from the local cache.
     *
     * @param  string  $name
     * @return Parser
     */
    protected function instance($name): Parser
    {
        return $this->instances[$name] ?? new $name();
    }
}