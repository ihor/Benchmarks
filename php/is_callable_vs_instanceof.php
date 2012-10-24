<?php

require_once 'utils/test.php';

class A {}

class AVeryLongClassNameThatIsNeededToTestPerformanceOfInstanceOf {}

$a = new A();
$b = new AVeryLongClassNameThatIsNeededToTestPerformanceOfInstanceOf();
$c = function() {};

function checkIsCallable($c) { return is_callable($c); }
function checkIsInstanceOf($i, $class) { return $i instanceof $class; }

$tests = 10;
$repeat = 10000;

foreach (array('Short class name' => $a, 'Long class name' => $b, 'Callable' => $c) as $test => $data) {
    say($test);
    tests('checkIsCallable', array($data), $tests, $repeat);
    tests('checkIsInstanceOf', array($data, get_class($data)), $tests, $repeat);
}
