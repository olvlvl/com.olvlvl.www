<?php

namespace ICanBoogie;

chdir(dirname(__DIR__));

require __DIR__ . '/../vendor/autoload.php';

try {
    boot()->run();
} catch (\Throwable $e) {
    if (!filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
        return;
    }

    header('Content-Type: text/plain');
    echo $e;
}
