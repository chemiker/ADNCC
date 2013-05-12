<?php
	require('pull-lib.php');
	
	foreach($key_value_pairs as $key => $value) {
		foreach($key_value_pairs[$key] as $key2 => $value2) {
		if($key2 !== "name") { 	
			if(substr_count(strtoupper(substr($value2, 0, 3)), "YES") OR substr_count(strtoupper(substr($value2, 0, 3)), "NO")) { //$value2 == "" OR 
				if($value2 == "") {
					
				} else {
					$key_value_pairs[$key][$key2] = substr($value2, 0, 3);
				}
			} else {
				unset($key_value_pairs[$key][$key2]);
			}
			}
		}
	}
	echo json_encode($key_value_pairs);
?>