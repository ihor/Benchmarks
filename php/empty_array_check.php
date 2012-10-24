<?php

require_once 'utils/test.php';
require_once 'utils/generators.php';

function empty_with_empty($array) {
    return empty($array);
}

function empty_with_count($array) {
    return count($array) === 0;
}

function empty_with_key($array) {
    reset($array);
    return null === key($array);
}

$tests = 10;
$repeat = 10000;

foreach (array('empty' => array(), 'full' => genDict()) as $test => $data) {
    say('Testing ' . $test);
    foreach (array(
        'empty_with_empty',
        'empty_with_count',
        'empty_with_key'
    ) as $func) {
        tests($func, array($data), $tests, $repeat, true);
    }
}