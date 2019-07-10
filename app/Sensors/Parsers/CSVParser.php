<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDataDTO;

class CSVParser extends BaseParser
{
    /**
     * {@inheritdoc}
     */
    protected function getDataFromInput(string $input): array
    {
        return \array_map('str_getcsv', \str_getcsv($input, PHP_EOL));
    }

    /**
     * {@inheritdoc}
     */
    protected function valid(array $data): bool
    {
        return !empty($data[0][0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function fillListWithData(array $data): void
    {
        foreach ($data as $measurements) {
            [$date, $city, $dayTemperature, $nightTemperature, $dayHumidity, $nightHumidity] = $measurements;
            $this->list->addDTO(new SensorDataDTO(
                new \DateTime($date),
                $city,
                (float)$dayTemperature,
                (float)$nightTemperature,
                (float)$dayHumidity,
                (float)$nightHumidity
            ));
        }
    }
}