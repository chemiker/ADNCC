<?php

	require('pull-lib.php');
	
 	echo "\n	<thead>\n	<tr>\n";
	
	foreach($header_dictionary as $title => $title_short) {
		echo "		<th>".$title."</th>\n";
	}
	
	if($_GET == array()) {
		$table_content = $key_value_pairs;
	} else {
		$table_content = $result;
	}
	
	//print_r($header_dictionary);
	
	echo "\n	</tr>\n	</thead>\n <tbody>\n";
	
?>