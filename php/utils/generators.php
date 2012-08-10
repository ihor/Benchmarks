<?php

function genStr($len = 6) {
    $validChars = 'abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789';
    $validCharsNumber = strlen($validChars);

    $result = '';

    for ($i = 0; $i < $len; $i++) {
        $index = mt_rand(0, $validCharsNumber - 1);
        $result .= $validChars[$index];
    }

    return $result;
}

function genDict($nestingLevelLimit = 5, $keysNumLimit = 20, $randKeysNum = false) {
    if (0 === $nestingLevelLimit) {
        return genStr();
    }

    $origKeysNumLimit = $keysNumLimit;
    if ($randKeysNum) {
        $keysNumLimit = mt_rand(1, $keysNumLimit);
    }

    $result = array();
    for ($i = 0; $i < $keysNumLimit; $i++) {
        $result[genStr()] = 1 === $nestingLevelLimit 
            ? genStr()
            : genDict(rand(0, $nestingLevelLimit - 1), $origKeysNumLimit, true);
    }

    return $result;
}
