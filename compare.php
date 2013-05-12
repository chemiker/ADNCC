<?php
	require('pull-lib.php');
	
		$platforms = "Android, iPhone/iPad, iPhone, OS X";
	
	function get_clients($platform, $data) {
		$returning_clients = array();
		foreach ($data as $key => $value) {
			if($data[$key]["platform"] == $platform) {
			//strpos($data[$key]["platform"], $platform) !== false
				array_push($returning_clients, $data[$key]);
			}
		}
		return $returning_clients;
	}
	

	
?>

<!doctype html>
<html lang="en-US" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<title>App.net Client Comparison</title>

<script src="jquery-1.9.1.min.js"></script>
<script src="jquery.tablesorter.js"></script>

<script>

	function absenden() {
	
		document.getElementById("client-table").innerHTML = "";
	
		formvalues = document.forms['selector'].elements;
		clients = new Array();
		var counter = "";
		

<?php

	foreach($key_value_pairs as $key => $value) {
		if(strpos($platforms, $key_value_pairs[$key]["platform"]) !== false) {
			$strip_names = str_replace(" ", "-", $key_value_pairs[$key]["name"]);
			echo "\n		if(formvalues['".$strip_names."'].checked == true) {
					clients.push('".$strip_names."');
			}\n";
		}
	}
?>
		

		for(i=0; i<clients.length; i++) {
			dataRequest('name=' + clients[i], i);
			
			//console.log(clients);
		}
		
				

	}
	
	
function dataRequest(url, i) {



	var request = "pull_html_light.php?" + url;
	$.ajax({
        url: request,
      type: "POST",
        success: function(data) {
        	
        		if(i == 0) {
        			var request2 = "pull_html_light_header.php";
					$.ajax({
						url: request2,
					  type: "POST",
						success: function(data) {
									document.getElementById("client-table").innerHTML += data;
						}
				   });	
        			document.getElementById("client-table").innerHTML += data;
        		} else {
        			document.getElementById("client-table").innerHTML += data;
        		}    	
        		
        		if(i == clients.length) {
        			document.getElementById("client-table").innerHTML += "</tbody>";
        		}
        			  		
        	$("#client-table").tablesorter();
        }
   });	
   



}


$(document).ready(function() 
    { 

    }
);
	
</script>

</head>

<body>

<form name="selector" style="padding: 0px 0px 50px 0px;"><pre>

<?php
	/*
	$platforms = array();

	foreach($key_value_pairs as $key => $value) {
	
		// Strip variables
		$stripped_var = str_replace("\n", "", $key_value_pairs[$key]["platform"]);
		
		if(in_array($stripped_var, $platforms)) {

		} else {
			array_push($platforms, $stripped_var);
		}
	}
	*/
	
	//print_r($key_value_pairs);
	

	
	foreach(explode(", ", $platforms) as $platform) {
		echo "<h1>".$platform."</h1>\n";
		foreach(get_clients($platform, $key_value_pairs) as $key => $value) {
			$strip_names = str_replace(" ", "-", $value["name"]);
			echo "	<input type=\"checkbox\" id=\"".$strip_names."\" name=\"".$strip_names."\"><label for=\"".$strip_names."\">".$value["name"]."</label><br />\n";
			
		}
	}
	
	
	
	//print_r(get_clients("Android", $key_value_pairs));
	
	

	//foreach($key_value_pairs as $key => $value) {
	//	echo "	<input type=\"checkbox\" id=\"".$key_value_pairs[$key]["name"]."\"><label for=\"".$key_value_pairs[$key]["name"]."\">".$key_value_pairs[$key]["name"]."</label><br />\n";
	//}
	
	//print_r($key_value_pairs[0]["name"]);
?>

	<input type="button" value="Absenden" onclick="absenden()" />

</form>

<div id="table_wrapper" style="overflow: auto;"><table id="client-table" class="tablesorter" class="hide"></table></div>

</body>