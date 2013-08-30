<?php

/**
 * @param string $message
 * @param int $indention
 */
function say($message, $indention = 0) {
    echo '[' . date('j M Y, G:i:s') . ']: ' . str_repeat('.', $indention * 3) . $message . "\r\n";
}

/**
 * @param string $func
 * @param array $args
 * @param int $repeat
 * @param bool $displayArgs
 * @param bool $displayResults
 * @return mixed
 */
function test($func, array $args, $repeat = 1000, $displayArgs = false, $displayResults = true) {
    $memory_start = memory_get_usage();
    $start = microtime(true);
    for ($i = 0; $i < $repeat; $i++) {
        call_user_func_array($func, (array) $args);
    }
    $in = microtime(true) - $start;
    $memory_in = memory_get_usage() - $memory_start;

    if ($displayResults) {
        say(sprintf(
            '%s(%s), %d times: %.2f ms, %d bytes',
            $func,
            $displayArgs ? preg_replace('[\[\]]', '', json_encode($args)) : '',
            $repeat,
            $in * 1000,
            $memory_in
        ));
    }

    return array($in, $memory_in);
}

/**
 * @param string $func
 * @param array $args
 * @param int $tests
 * @param int $repeat
 * @param bool $onlyAverage
 * @param bool $displayArgs
 * @return float
 */
function tests($func, array $args, $tests = 10, $repeat = 1000, $onlyAverage = false, $displayArgs = false) {
    $in = 0;
    $memory_in = 0;
    for ($i = 0; $i < $tests; $i++) {
        list($test_in, $test_memory_in) = test($func, $args, $repeat, $displayArgs, !$onlyAverage);
        $in += $test_in;
        $memory_in += $test_memory_in;
    }

    $average = $in * 1000 / $tests;
    $average_memory = $memory_in / $tests;
    if ($onlyAverage) {
        say(sprintf(
            '%d tests, %s(%s), %d times: %.2f ms, %d bytes',
            $tests,
            $func,
            $displayArgs ? preg_replace('[\[\]]', '', json_encode($args)) : '',
            $repeat,
            $average,
            $average_memory
        ));
    }
    else {
        say(sprintf('Average: %.2f ms, %d bytes', $average, $average_memory));
    }

    return $average;
}
