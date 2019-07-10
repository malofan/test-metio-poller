<?php

namespace App\Sensors;


class SensorDataDTOList implements \Countable, \Iterator
{
    /**
     * @var SensorDataDTO[]
     */
    private $dtoList = [];

    /**
     * @var int
     */
    private $currentIndex = 0;

    public function addDTO(SensorDataDTO $dto): void
    {
        $this->dtoList[] = $dto;
    }

    public function count(): int
    {
        return \count($this->dtoList);
    }

    public function current(): SensorDataDTO
    {
        return $this->dtoList[$this->currentIndex];
    }

    public function clean(): void
    {
        $this->dtoList = [];
    }

    public function key(): int
    {
        return $this->currentIndex;
    }

    public function next()
    {
        $this->currentIndex++;
    }

    public function rewind()
    {
        $this->currentIndex = 0;
    }

    public function valid(): bool
    {
        return isset($this->dtoList[$this->currentIndex]);
    }
}