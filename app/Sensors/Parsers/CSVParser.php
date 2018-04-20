<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDTO;

class CSVParser implements Parser
{
    /**
     * @param string $input
     * @return SensorDTO[]
     */
    public function parse(string $input): array
    {
        $output = [];
        $data = array_map('str_getcsv', str_getcsv($input, PHP_EOL));

        if (empty($data) || !is_array($data)) {

            return $output;
        }

        foreach ($data as $measurements) {
            [$date, $city, $dayTemperature, $nightTemperature, $dayHumidity, $nightHumidity] = $measurements;
            $output[] = new SensorDTO(
                new \DateTime($date),
                $city,
                $dayTemperature,
                $nightTemperature,
                $dayHumidity,
                $nightHumidity
            );
        }

        return $output;
    }
}