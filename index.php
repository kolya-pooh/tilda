<?php

require_once __DIR__ . '/vendor/autoload.php';

use Tilda\Tasks\FirstTask;
use Tilda\Tasks\SecondTask;
use Tilda\Tasks\TaskExecutor;
use Tilda\Tasks\ThirdTask;

try {
    $task = match ($argv[1] ?? null) {
        '1' => new FirstTask(),
        '2' => new SecondTask(),
        '3' => new ThirdTask(),
        default => throw new \Exception('Bad task number'),
    };

    (new TaskExecutor($task))->execute();
} catch(\Throwable $e) {
    echo "Something went wrong: " . $e->getMessage() . "\n";
}