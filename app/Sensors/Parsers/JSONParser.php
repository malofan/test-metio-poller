<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDTO;

class JSONParser implements Parser
{
    /**
     * @param string $input
     * @return SensorDTO[]
     */
    public function parse(string $input): array
    {
        $output = [];
        $data = \json_decode($input, true);

        if (empty($data) || !is_array($data)) {

            return $output;
        }

        $measureDate = new \DateTime(\current(\array_keys($data)));

        foreach (\current($data) as $city => $measurements) {
            $day = $measurements['day'];
            $night = $measurements['night'];
            $output[] = new SensorDTO(
                $measureDate,
                $city,
                $day['temperature'],
                $day['humidity'],
                $night['temperature'],
                $night['humidity']
            );
        }

        return $output;
    }
}