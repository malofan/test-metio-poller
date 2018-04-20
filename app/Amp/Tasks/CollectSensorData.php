<?php

namespace App\Amp\Tasks;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;

class CollectSensorData implements Task
{
    public function __construct(callable $function, ...$args) {
        $this->function = $function;
        $this->args = $args;
    }
    /**
     * {@inheritdoc}
     */
    public function run(Environment $environment) {
        return ($this->function)(...$this->args);
    }
}