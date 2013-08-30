<?php

require_once 'utils/test.php';
require_once 'utils/generators.php';

function regex_filter($string) {
	preg_match_all("/(fuck|niggers|ass|bitch|penis|vagina|fack|niger|niggers|fak|shit)/i", $string, $matches);
	foreach ($matches[0] as $match) {
		$string = str_replace($match, str_repeat('*', strlen($match)), $string);
	}
	return $string;
}

function isset_in_array_filter($string) {
	$words = explode(' ', $string);
	$badWords = array('fuck' => 1, 'niggers' => 1, 'bitch' => 1, 'ass' => 1, 'penis' => 1, 'vagina' => 1, 'fack' => 1, 'nigger' => 1, 'shit' => 1, 'fak' => 1, 'fuck' => 1, 'niggers' => 1, 'bitch' => 1, 'ass' => 1, 'penis' => 1, 'vagina' => 1, 'fack' => 1, 'nigger' => 1, 'shit' => 1, 'fak' => 1,);
	foreach ($words as $word) {
		if (isset($badWords[$word])) {
			$string = str_replace($word, str_repeat('*', strlen($word)), $string);
		}
	}
	return $string;
}

function isset_in_array_filter_prepared($string, $badWords) {
	$words = explode(' ', $string);
	foreach ($words as $word) {
		if (isset($badWords[$word])) {
			$string = str_replace($word, str_repeat('*', strlen($word)), $string);
		}
	}
	return $string;
}

function boxwood_filter($string) {
	$r = boxwood_new();
	boxwood_add_text($r, 'fuck');
	boxwood_add_text($r, 'niggers');
	boxwood_add_text($r, 'bitch');
	boxwood_add_text($r, 'ass');
	boxwood_add_text($r, 'penis');
	boxwood_add_text($r, 'vagina');

	return boxwood_replace_text($r, $string, '*');
}

function boxwood_filter_prepared($string, $boxwood) {
	return boxwood_replace_text($boxwood, $string, '*');
}

$boxwood = boxwood_new();
boxwood_add_text($boxwood, 'fuck');
boxwood_add_text($boxwood, 'niggers');
boxwood_add_text($boxwood, 'bitch');
boxwood_add_text($boxwood, 'ass');
boxwood_add_text($boxwood, 'penis');
boxwood_add_text($boxwood, 'vagina');
boxwood_add_text($boxwood, 'fak');
boxwood_add_text($boxwood, 'nigger');
boxwood_add_text($boxwood, 'niger');
boxwood_add_text($boxwood, 'fack');
boxwood_add_text($boxwood, 'shit');

boxwood_add_text($boxwood, 'fuck1');
boxwood_add_text($boxwood, 'niggers1');
boxwood_add_text($boxwood, 'bitch1');
boxwood_add_text($boxwood, 'ass1');
boxwood_add_text($boxwood, 'penis1');
boxwood_add_text($boxwood, 'vagina1');
boxwood_add_text($boxwood, 'fak1');
boxwood_add_text($boxwood, 'nigger1');
boxwood_add_text($boxwood, 'niger1');
boxwood_add_text($boxwood, 'fack1');
boxwood_add_text($boxwood, 'shit1');

boxwood_add_text($boxwood, 'fuck2');
boxwood_add_text($boxwood, 'niggers2');
boxwood_add_text($boxwood, 'bitch2');
boxwood_add_text($boxwood, 'ass2');
boxwood_add_text($boxwood, 'penis2');
boxwood_add_text($boxwood, 'vagina2');
boxwood_add_text($boxwood, 'fak2');
boxwood_add_text($boxwood, 'nigger2');
boxwood_add_text($boxwood, 'niger2');
boxwood_add_text($boxwood, 'fack2');
boxwood_add_text($boxwood, 'shit2');

$badWords = array('fuck' => 1, 'niggers' => 1, 'bitch' => 1, 'ass' => 1, 'penis' => 1, 'vagina' => 1);

$tests = 3;
$repeat = 10000;

tests('regex_filter', array('fuck you all in the ass niggers'), $tests, $repeat);
tests('isset_in_array_filter', array('fuck you all in the ass niggers'), $tests, $repeat);
tests('isset_in_array_filter_prepared', array('fuck you all in the ass niggers', $badWords), $tests, $repeat);
tests('boxwood_filter', array('fuck you all in the ass niggers'), $tests, $repeat);
tests('boxwood_filter_prepared', array('fuck you all in the ass niggers', $boxwood), $tests, $repeat);
