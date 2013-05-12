<?php

include("pull-lib.php");

foreach($header_dictionary as $key => $value) {
	//echo "		if(formvalues['".$value."'].checked == true) { 
	//		if(url == \"\") {
	//			url += '".$value."=YES';
	//		} else {
	//			url += '&".$value."=YES';
	//		}
	//	}\n\n";
	
	echo "<input type=\"checkbox\" name=\"".$value."\" /> <label for=\"".$value."\">".$value."</label><br />\n";
	
}

?>