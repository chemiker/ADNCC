<?php

	require('pull-lib.php');
	
	foreach ($result as $key => $value) {
		
		$developer = explode(', ', $value["developer_accounts"]);
		$i = 1;
		
		//print_r($developer[0]);
		
		$date_version = str_split($value["last_updated"], 10);
		echo "<div class=\"listed-client\">\n";
		echo "	<div class=\"left\">";
		echo "		<h4><a href=\"".$value["download_link"]."\" title=\"Download: ".$value["name"]."\">".$value["name"]." <span class=\"download\"></span></a></h4>";
		echo "		On ".$value["platform"];
		if($value["minimum_os_version"] !== "") {
			echo " (System Version: ".$value["minimum_os_version"].")<br />\n";
		} else {
			echo "<br />";
		}
		if($value["supported_languages"] !== "") {
			echo "		Available in ".$value["supported_languages"]."\n";
		}
		echo "	</div>";
		echo "	<div class=\"right\">";
		if(count($developer) == 1) {
			echo "		Developer account: <a href=\"https://alpha.app.net/".trim($developer[0], "@")."\">".$developer[0]."</a><br />";
		} else {
			echo "		Developer accounts: ";
			foreach ($developer as $developer_entry) {
					if($i < count($developer)) {
						echo "		<a href=\"https://alpha.app.net/".trim($developer_entry, "@")."\">".$developer_entry."</a>, ";
					} else {
						echo "		<a href=\"https://alpha.app.net/".trim($developer_entry, "@")."\">".$developer_entry."</a>";
					}
					$i++;
			}
			echo "<br />\n";
		}
		echo "		Latest update: ".date("Y-m-d", strtotime($date_version[0]));
		if($date_version[1] !== "") {
			$version = str_replace("(", "", $date_version[1]);
			$version = str_replace(")", "", $version);
			//echo substr($version,1,strlen($version)-1);
			echo"	 (Version ".substr($version,1,strlen($version)-1).")<br />\n";
		}
		echo "		".$value["misc_notes"]."\n";
		echo "	</div>";
		echo "</div>\n<hr />\n";
	}

	//print_r($result);
	
?>