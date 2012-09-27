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
    $start = microtime(true);
    for ($i = 0; $i < $repeat; $i++) {
        call_user_func_array($func, (array) $args);
    }
    $in = microtime(true) - $start;

    if ($displayResults) {
        say(sprintf(
            '%s(%s), %d times: %.2f ms',
            $func,
            $displayArgs ? preg_replace('[\[\]]', '', json_encode($args)) : '',
            $repeat,
            $in * 1000
        ));
    }

    return $in;
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
    for ($i = 0; $i < $tests; $i++) {
        $in += test($func, $args, $repeat, $displayArgs, !$onlyAverage);
    }

    $average = $in * 1000 / $tests;
    if ($onlyAverage) {
        say(sprintf(
            '%d tests, %s(%s), %d times: %.2f ms',
            $tests,
            $func,
            $displayArgs ? preg_replace('[\[\]]', '', json_encode($args)) : '',
            $repeat,
            $average
        ));
    }
    else {
        say(sprintf('Average: %.2f ms', $average));
    }

    return $average;
}
