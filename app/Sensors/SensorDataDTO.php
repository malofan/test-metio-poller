<?php

namespace App\Sensors;


class SensorDataDTO
{
    /**
     * @var string
     */
    private $city;
    /**
     * @var float
     */
    private $dayTemperature;
    /**
     * @var float
     */
    private $dayHumidity;
    /**
     * @var float
     */
    private $nightTemperature;
    /**
     * @var float
     */
    private $nightHumidity;
    /**
     * @var string
     */
    private $date;


    public function __construct(
        \DateTime $date,
        string $city,
        float $dayTemperature,
        float $dayHumidity,
        float $nightTemperature,
        float $nightHumidity
    ) {
        $this->city = $city;
        $this->date = $date;
        $this->dayTemperature = $dayTemperature;
        $this->dayHumidity = $dayHumidity;
        $this->nightTemperature = $nightTemperature;
        $this->nightHumidity = $nightHumidity;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date->format('Y-m-d');
    }

    /**
     * @return float
     */
    public function getDayTemperature(): float
    {
        return $this->dayTemperature;
    }

    /**
     * @return float
     */
    public function getDayHumidity(): float
    {
        return $this->dayHumidity;
    }

    /**
     * @return float
     */
    public function getNightTemperature(): float
    {
        return $this->nightTemperature;
    }

    /**
     * @return float
     */
    public function getNightHumidity(): float
    {
        return $this->nightHumidity;
    }


}