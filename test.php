<?php

$array = array();

$array[0] = array(1, 2, 3, 4);
$array[1] = array(1, 7, 3, 4);
$array[2] = array(1, 7, 3, 11);

unset($array[2]);

echo "<pre>";
print_r($array);

?>