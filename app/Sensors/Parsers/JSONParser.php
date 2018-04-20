<?php

namespace App\Sensors\Parsers;


use App\Sensors\SensorDataDTO;

class JSONParser extends BaseParser
{
    /**
     * {@inheritdoc}
     */
    protected function getDataFromInput(string $input): array
    {
        return (array)\json_decode($input, true);
    }

    /**
     * {@inheritdoc}
     */
    protected function valid(array $data): bool
    {
        return !empty($data) && \strtotime(\current(\array_keys($data))) !== false;
    }

    /**
     * {@inheritdoc}
     */
    protected function fillListWithData(array $data): void
    {
        $measureDate = new \DateTime(\current(\array_keys($data)));

        foreach (\current($data) as $city => $measurements) {
            $day = $measurements['day'];
            $night = $measurements['night'];
            $this->list->addDTO(new SensorDataDTO(
                $measureDate,
                $city,
                (float)$day['temperature'],
                (float)$day['humidity'],
                (float)$night['temperature'],
                (float)$night['humidity']
            ));
        }
    }
}