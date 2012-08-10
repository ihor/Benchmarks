<?php

require_once 'utils/test.php';
require_once 'utils/generators.php';

function generateData() {
    $data = array();
    for ($i = 0; $i < 5; $i++) {
        $value = genStr();
        for ($j = 0; $j < mt_rand(0, 100); $j++) {
            $data[] = $value;
        }
    }
    shuffle($data);
    
    return $data;
}

function countValuesIn($data) {
    $result = array();
    foreach ($data as $value) {
        if (!isset($result[$value])) {
            $result[$value] = 0;
        }

        $result[$value]++;
    }
    
    return $result;
}

$data = generateData();
$tests = 10;
$repeat = 10000;

tests('countValuesIn', array($data), $tests, $repeat);
tests('array_count_values', array($data), $tests, $repeat);