<?php

/**
 * @param int $len
 * @return string
 */
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

/**
 * @param int $size
 * @param int $nestingLevel
 * @param bool $randSize
 * @return array|string
 */
function genDict($size = 20, $nestingLevel = 5, $randSize = false) {
    if (0 === $nestingLevel) {
        return genStr();
    }

    $origSize = $size;
    if ($randSize) {
        $size = mt_rand(1, $size);
    }

    $result = array();
    for ($i = 0; $i < $size; $i++) {
        $result[genStr()] = 1 === $nestingLevel
            ? genStr()
            : genDict($origSize, rand(0, $nestingLevel - 1), true);
    }

    return $result;
}

/**
 * @param int $size
 * @return array
 */
function genList($size) {
    $result = array();
    for ($i = 0; $i < $size; $i++) {
        $result[] = genStr();
    }

    return $result;
}
