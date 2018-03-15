<?php
$array1 = ['aaaa', 'bbbb'];
$array2 = ['cccc', 'dddd'];
$array3 = ['eeee', 'ffff'];
$array4 = ['gggg', 'hhhh'];

$array12 = [
	$array1, $array2
];
$array34 = [
	$array3, $array4
];

var_dump($array1, $array2);
var_dump($array1 + $array2);
var_dump(array_merge($array1, $array2));


var_dump($array12, $array34);
var_dump($array12 + $array34);
var_dump(array_merge($array12, $array34));