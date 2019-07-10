<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDataDTOList;

abstract class BaseParser implements Parser
{
    /**
     * @var SensorDataDTOList
     */
    protected $list;
    
    public function __construct(SensorDataDTOList $list)
    {
        $this->list = $list;
    }

    /**
     * @param string $input
     * @return SensorDataDTOList
     */
    public function parse(string $input): SensorDataDTOList
    {
        
        $data = $this->getDataFromInput($input);

        if ($this->valid($data)) {
            $this->list->clean();
            $this->fillListWithData($data);
        }

        return $this->list;
    }

    /**
     * @param string $input
     * @return array
     */
    abstract protected function getDataFromInput(string $input): array;

    /**
     * @param array $data
     * @return bool
     */
    abstract protected function valid(array $data): bool;

    /**
     * @param array $data
     * @param $data
     */
    abstract protected function fillListWithData(array $data): void;
}