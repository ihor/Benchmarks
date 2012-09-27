<?php

require_once 'utils/test.php';
require_once 'utils/generators.php';

function for_next_check($array) {
  for (reset($array), $base = 0; key($array) === $base++; next($array));
  return is_null(key($array));
}

function array_key_exists_check($array) {
    for ($index = 0, $maxIndex = count($array) - 1; $index <= $maxIndex; $index++) {
        if (!array_key_exists($index, $array)) {
            return true;
        }
    }

    return false;
}

function array_filter_check($array) {
    return (bool)count(array_filter(array_keys($array), 'is_string'));
}

function array_values_check($array) {
    return ($array !== array_values($array));
}

function array_keys_check($array) {
    $array = array_keys($array);
    return ($array !== array_keys($array));
}

function range_check($array) {
    return array_keys($array) !== range(0, count($array) - 1);
}

$dict = genDict(20, 1);
$list = genList(20);
$tests = 5;
$repeat = 10000;

foreach (array('dictionary' => $dict, 'list' => $list) as $test => $data) {
    say('Testing with ' . $test);

    $min = PHP_INT_MAX;
    $theFastestFunc = '';

    foreach (array(
        'array_values_check',
        'array_key_exists_check',
        'array_keys_check',
        'for_next_check',
        'range_check',
        'array_filter_check',
    ) as $func) {
        $average = tests($func, array($data), $tests, $repeat, true);
        if ($average < $min) {
            $min = $average;
            $theFastestFunc = $func;
        }
    }

    say('The fastest function is ' . $theFastestFunc);

    echo "\n";
}
