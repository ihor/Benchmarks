<?php

require_once 'utils/test.php';
require_once 'utils/generators.php';

$data = genDict(1000, 1);
$tests = 10;
$repeat = 10000;

tests('count', array($data), $tests, $repeat, true);
tests('sizeof', array($data), $tests, $repeat, true);