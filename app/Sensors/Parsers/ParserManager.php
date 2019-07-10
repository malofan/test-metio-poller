<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDataDTOList;

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
     * @return SensorDataDTOList
     *
     * @throws UnparseableInputException
     */
    public function parse($input): SensorDataDTOList
    {
        foreach ($this->parsers as $parser) {
            $this->instances[$parser] = $this->instance($parser);
            $output = $this->instances[$parser]->parse($input);

            if ($output->valid()) {

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
        return $this->instances[$name] ?? $this->resolve($name);
    }

    protected function resolve($name)
    {
        $parser = (new \ReflectionClass($name))->getShortName();

        $clientMethod = 'create'.$parser;

        if (method_exists($this, $clientMethod)) {
            return $this->{$clientMethod}();
        }

        throw new \InvalidArgumentException(sprintf("Parser [%s] is not supported.", $name));
    }

    protected function createJSONParser(): Parser
    {
        return new JSONParser(new SensorDataDTOList());
    }

    protected function createCSVParser(): Parser
    {
        return new CSVParser(new SensorDataDTOList());
    }
}