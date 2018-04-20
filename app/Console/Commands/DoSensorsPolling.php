<?php

namespace App\Console\Commands;

use App\CityAlias;
use App\Sensor;
use App\SensorData;
use App\Sensors\Parsers\ParserManager;
use App\Sensors\Parsers\UnparseableInputException;
use App\Sensors\SensorDataDTOList;
use Illuminate\Console\Command;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;

class DoSensorsPolling extends Command
{
    const REQUEST_TIMEOUT = 10;

    /**
     * @var ParserManager
     */
    private $parserManager;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensors:poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @param ParserManager $parserManager
     * @param HttpClient $httpClient
     * @return void
     */
    public function __construct(ParserManager $parserManager, HttpClient $httpClient)
    {
        parent::__construct();

        $this->httpClient = $httpClient;
        $this->parserManager = $parserManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cities = CityAlias::all()->mapWithKeys(function ($city) {
            return [$city->name => $city->city_id];
        });

        foreach (Sensor::all() as $sensor) {
            try {
                $response = $this->httpClient->get(
                    $sensor->url,
                    [
                        'timeout' => self::REQUEST_TIMEOUT
                    ]
                );
                /**
                 * @var SensorDataDTOList
                 */
                $sensorData = $this->parserManager->parse($response->getBody());

                foreach ($sensorData as $sensorDTO) {
                    SensorData::create([
                        'sensor_id' => $sensor->id,
                        'date' => $sensorDTO->getDate(),
                        'city_id' => $cities->get($sensorDTO->getCity()),
                        'day_temperature' => $sensorDTO->getDayTemperature(),
                        'night_temperature' => $sensorDTO->getNightTemperature(),
                        'day_humidity' => $sensorDTO->getDayHumidity(),
                        'night_humidity' => $sensorDTO->getNightHumidity(),
                    ]);
                }
            }
            catch (\GuzzleHttp\Exception\RequestException $e) {
                Log::warning(sprintf(
                    'There is no response from Sensor with ID %d in %d seconds.' . PHP_EOL
                    . 'Reason message: %s',
                    $sensor->id,
                    self::REQUEST_TIMEOUT,
                    $e->getMessage()
                ));
            }
            catch (UnparseableInputException $e) {
                Log::warning(sprintf(
                    'Response from Sensor with ID %d cannot be parsed.' . PHP_EOL
                    . 'Parser exception:' . PHP_EOL
                    . ' %s',
                    $sensor->id,
                    $e->getMessage()
                ));
            }
            catch (\Illuminate\Database\QueryException $e) {

            }
            catch (\Exception $e) {
                Log::warning($e->getMessage());
            }
        }
    }
}
