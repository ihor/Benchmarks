<?php

function say($message, $indention = 0) {
    echo '[' . date('j M Y, G:i:s') . ']: ' . str_repeat('.', $indention * 3) . $message . "\r\n";
}

function test($func, $args, $repeat = 1000, $displayArgs = false) {
    $start = microtime(true);
    for ($i = 0; $i < $repeat; $i++) {
        call_user_func_array($func, (array) $args);
    }
    $in = microtime(true) - $start;

    say(sprintf(
        '%s(%s), %d times: %.2f ms', 
        $func, 
        $displayArgs ? preg_replace('[\[\]]', '', json_encode($args)) : '', 
        $repeat, 
        $in * 1000
    ));

    return $in;
}

function tests($func, $args, $tests = 10, $repeat = 1000, $displayArgs = false) {
    $in = 0;
    for ($i = 0; $i < $tests; $i++) {
        $in += test($func, $args, $repeat, $displayArgs);
    }
    
    say(sprintf('Average: %.2f ms', $in * 1000 / $tests));
}

function cases($func, $cases, $repeat = 1000, $displayArgs = false) {
    foreach ($cases as $args) {
        test($func, $args, $repeat, $displayArgs);
    }
}
