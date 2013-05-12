<?php
$f = fopen("https://docs.google.com/spreadsheet/pub?key=0AjVIXWN8HnOedDZsOWY4SlNyaElQMElQN1FNbktFZGc&output=csv", "r"); // Spreadsheet @ google docs

$raw_data = array(); // raw data from google docs
$raw_header = fgetcsv($f);
$header_dictionary = array(); // Long and short term header variables
$key_value_pairs = array(); // short header and client data merged
$empty_array = array('a');
$anfrage = array(); // Anfrage bezüglich möglicher Clients
$raw_result = array();
$hilfsauflistung = array(); // Zuweisen einer ID zu jedem Namen
$delete = array(); // Mark arrays to delete
$result = array();

// Kick out Headlines

function kick_headlines($line) {
	if($line !== "") {
		return $line;
	}
}

while (($line = fgetcsv($f)) !== false) {
	$line_without_empty = array_filter($line, "kick_headlines");
	if(count($line_without_empty) > 1) {
		array_push($raw_data, $line); // Attaching each line to $raw_data
	}
}

// Parsing header titles and create header_dictionary and final header

function parse_header($raw) {
	$first_braket = stripos($raw, "("); // Suche nach Klammern 
	if($first_braket !== false) {
		$short_raw = substr($raw, 0, $first_braket); // Schneide Klammern weg
	} else {
		$short_raw = $raw; // Wenn keine Klammern vorhanden, tuh nichts
	}
	$short_title = strtolower(str_replace(" ", "_", $short_raw));
	// Abschließend das letzte _ , ", ', Punkte entfernenn.
	$short_title = str_replace(".", "", $short_title);
	$short_title = str_replace("\"", "", $short_title);
	$short_title = str_replace("'", "", $short_title);
	$short_title = str_replace("@", "", $short_title);
	$short_title = str_replace("-", "_", $short_title);
	if(substr($short_title, -1) === "_") {
		$short_title = substr($short_title, 0, strrchr($short_title, "_") - 1);
	}
	return $short_title;	
}

foreach($raw_header as $raw_title) {
	array_push($header_dictionary, parse_header($raw_title)); 
}

// Erzeuge Header-dictionary: Zuweisung der Abkürzungen zu den Namen
$header_dictionary = array_combine($raw_header, $header_dictionary);

// Erzeuge aus $raw_data und dem dictionary key-value-pairs
foreach($raw_data as $raw_entry) {
	$combi = array_combine($header_dictionary, $raw_entry); 
	array_push($key_value_pairs, $combi); 
}

if($_GET !== array()) {  
	// Doing $_GET magic to get an idea about the query
	foreach($_GET as $key => $call) {
		//if(in_array($call, $header_dictionary)) {
			$anfrage = array_merge($anfrage, array($key => $_GET[$key]));
		//}
	}

	// Hilfsauflistung bauen
	//foreach($key_value_pairs as $entry) {
	//	array_push($hilfsauflistung, $entry["name"]);
	//}

	foreach($anfrage as $key_anfrage => $pair_anfrage) {
		foreach($key_value_pairs as $key_data => $pair_data) {
			//if($anfrage[$key_anfrage] === $pair_data[$key_anfrage]) {
			//echo strpos($pair_data[$key_anfrage], $anfrage[$key_anfrage]);
			if(strpos($pair_data[$key_anfrage], $anfrage[$key_anfrage]) !== false) {
			} else {
				array_push($delete, $pair_data["name"]);		
			}
		}
	}

	// Doppelte Werte entfernen
	$delete = array_unique($delete);

	foreach($key_value_pairs as $key => $client) {
		if(!in_array($client["name"], $delete)) {
			array_push($result, $client);
		}
	} 
}
?>