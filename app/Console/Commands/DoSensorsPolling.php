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

use App\Amp\Tasks\CollectSensorData;
use Amp\Coroutine;
use Amp\Loop;
use Amp\Parallel\Worker\DefaultPool;
use function Amp\Promise\all as promiseAll;


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
     */
    public function handle()
    {
        $cities = CityAlias::all()->mapWithKeys(function ($city) {
            return [$city->name => $city->city_id];
        });

        $tasks = Sensor::all()
            ->mapWithKeys(function ($sensor) {
                return [$sensor->id => new CollectSensorData('file_get_contents', $sensor->url)];
            })
            ->all();

        Loop::run(function () use (&$tasks, $cities) {
            $timer = Loop::repeat(200, function () {
                printf(".");
            });
            Loop::unreference($timer);
            $pool = new DefaultPool;
            $coroutines = [];
            foreach ($tasks as $sensorId => $task) {
                $coroutines[] = function () use ($pool, $sensorId, $task, $cities) {
                    $response =  yield $pool->enqueue($task);

                    try {
                        /**
                         * @var SensorDataDTOList
                         */
                        $sensorData = $this->parserManager->parse($response);

                        foreach ($sensorData as $sensorDTO) {
                            SensorData::create([
                                'sensor_id' => $sensorId,
                                'date' => $sensorDTO->getDate(),
                                'city_id' => $cities->get($sensorDTO->getCity()),
                                'day_temperature' => $sensorDTO->getDayTemperature(),
                                'night_temperature' => $sensorDTO->getNightTemperature(),
                                'day_humidity' => $sensorDTO->getDayHumidity(),
                                'night_humidity' => $sensorDTO->getNightHumidity(),
                            ]);
                        }
                    }
                    catch (UnparseableInputException $e) {
                        Log::warning(sprintf(
                            'Response from Sensor with ID %d cannot be parsed.' . PHP_EOL
                            . 'Parser exception:' . PHP_EOL
                            . ' %s',
                            $sensorId,
                            $e->getMessage()
                        ));
                    }
                    catch (\Illuminate\Database\QueryException $e) {

                    }
                    catch (\Exception $e) {
                        Log::warning($e->getMessage());
                    }
                };
            }
            $coroutines = array_map(function (callable $coroutine): Coroutine {
                return new Coroutine($coroutine());
            }, $coroutines);
            yield promiseAll($coroutines);
            return yield $pool->shutdown();
        });
    }
}
