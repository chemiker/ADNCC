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
	
	echo "\n	</tr>\n	</thead>\n	<tbody>\n";
	
	foreach($table_content as $key => $entry) {
		echo "\n	<tr>\n";
		foreach($entry as $deeper_key => $deeper_entry) {		
			if($deeper_key !== "name") {
				if(substr_count(strtoupper(substr($deeper_entry, 0, 3)), "YES") >= 1 OR substr_count(strtoupper(substr($deeper_entry, 0, 3)), "NO") >= 1 OR substr_count(strtoupper(substr($deeper_entry, 0, 3)), "N/A") >= 1 OR substr_count(strtoupper(substr($deeper_entry, 0, 3)), "PAR") >= 1) {
					if(substr_count(strtoupper(substr($deeper_entry, 0, 3)), "PAR") >= 1 OR substr_count(strtoupper(substr($deeper_entry, 0, 3)), "N/A") >= 1) {
						   echo "		<td class=\"na $deeper_key\">$deeper_entry</td>\n";
					}
					if(substr_count(strtoupper(substr($deeper_entry, 0, 3)), "YES") >= 1) {
					   if(substr_count(strtoupper(substr($deeper_entry, 0, 3)), " YES ") >= 1 OR substr_count(strtoupper(substr($deeper_entry, 0, 3)), "YES ") >= 1 OR strtoupper(substr($deeper_entry, 0, 3)) == "YES") {
						   echo "		<td class=\"yes $deeper_key\">$deeper_entry</td>\n";
						}
					}
					if(substr_count(strtoupper($deeper_entry), "NO") >= 1) {
						if(substr_count(strtoupper(substr($deeper_entry, 0, 3)), "NO ") >= 1 OR substr_count(strtoupper(substr($deeper_entry, 0, 3)), " NO ") >= 1 OR strtoupper(substr($deeper_entry, 0, 3)) == "NO") {
						   echo "		<td class=\"no $deeper_key\">$deeper_entry</td>\n";
						}
					}
				}else {
					echo "		<td>$deeper_entry</td>\n";
				}
			} else {
				echo "		<td>$deeper_entry</td>\n";
			}       	
		}
		echo "\n	</tr>";
	}
	
	echo "\n	</tbody>\n";
?>