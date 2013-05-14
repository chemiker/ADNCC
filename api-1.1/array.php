<?php
	ini_set('display_errors', 1);

	$f = fopen("https://docs.google.com/spreadsheet/pub?key=0AjVIXWN8HnOedDZsOWY4SlNyaElQMElQN1FNbktFZGc&output=csv", "r"); // Spreadsheet @ google docs
	$raw_header = fgetcsv($f);

	/* TO DO:
	
		- Match show & hide with header_dictionary
		- Screenshots und Descr. an Clients koppeln.
		
	*/
	
	// Kickt Headlines
	function kick_headlines($line) {
		if($line !== "") {
			return $line;
		}
	}
	
	// Überprüft ob ein String in Zeichenkette vorhanden ist
	function str_vorhanden($string,$match) {
		if (strpos(strtoupper($string), strtoupper($match)) !== false) {
			return 1;
		} else {
			return 0;
		}
	}
	
	// Erstellt header_dictionary
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
	
	// Kickt alle Clients, welche spezifische Bedingungen nicht erfüllen
	
	function kick_clients_by_properties($clients, $required_properties) {
		$finish_him = array();
		foreach($clients as $id => $fields) {
			foreach($required_properties as $requirement => $value) {		
				if(isset($clients[$id][$requirement])) {
					if(!str_vorhanden($clients[$id][$requirement], $required_properties[$requirement])) {
						unset($clients[$id]);
					}
				}
			} 
		}
		return $clients;
	}

	// Leere arrays
	$header_dictionary = array(); // Liste von Values und deren Beschreibung
	$raw_data = array(); // Rohdaten
	$key_value_pairs = array(); // Verarbeitete Rohdaten
	$names = array(); // Liste von Clients
	$show = array(); // Spalten, welche gezeigt werden sollen
	$hide = array(); // Spalten, welche nicht gezeigt werden sollen
	$raw_result = array(); // Gefilterte Daten, allerdings noch nicht in der finalen Darstellung
	$filter = array(); // Eingebene Filter 
	$result = array();

	// Mögliche GET Anfragen
	$possible_get = array("name","show","hide","format","filter");
	
	// Mögliche output formate
	$possible_output_format = array("json","js","php","html","header","hAppy");
	
	// Headlines entfernen und Rohdaten anlegen
	while (($line = fgetcsv($f)) !== false) {
		$line_without_empty = array_filter($line, "kick_headlines");
		if(count($line_without_empty) > 1) {
			array_push($raw_data, $line); // Attaching each line to $raw_data
		}
	}
	
	// Header-dictionary erstellen
	foreach($raw_header as $raw_title) {
		array_push($header_dictionary, parse_header($raw_title)); 
	}
	$header_dictionary = array_combine($raw_header, $header_dictionary);
	
	// Dictionary und $raw_data nutzen um finale Werte zu bauen
	
	foreach($raw_data as $raw_entry) {
		$combi = array_combine($header_dictionary, $raw_entry); 
		array_push($key_value_pairs, $combi);
	}
	
	// Überprüfungen killen script, wenn etwas nicht stimmt.

	// URL auslesen
	if(count($_GET) == 0) {
		// Nicht genügend informationen um API call auszuführen.
		echo "<strong>Error:</strong> Please give more information. At least require a specific 'format'!";
		exit();
	} else {
		// $_GET ist vorhanden, wird nun auf konsistenz überprüft
		foreach($_GET as $call => $information) {
			// Überprüfen auf nicht vorhandene Filter
			if(!in_array($call, $possible_get)) {
				// Fehler: Objekt nicht vorhanden
				echo "<strong>Error:</strong> You called a non supported type of filter!";
				exit();
			}
			// Format angegeben?
			if(count($_GET["format"]) == 0) {
				// Fehler: Objekt nicht vorhanden
				echo "<strong>Error:</strong> You need to name an output format!";
				exit();
			}			
		}
		// Alles ok bis hierhin.
	}
	
	
	// Filter- und Spaltenoperationen
	
	// Liste von Namen 
	if(isset($_GET["name"]) AND str_vorhanden($_GET["name"], ",")) {
		$names = array_unique(explode(",", $_GET["name"]));
	}
	// Spalten, welche gezeigt werden sollen
	if(isset($_GET["show"]) AND $_GET["show"] !== "") {
		if(str_vorhanden($_GET["show"], ",")) {
			$show = array_unique(explode(",", $_GET["show"]));
		} else {
			$show = array($_GET["show"]);
		}
	}
	// Spalten, welche nicht gezeigt werden sollen	
	if(isset($_GET["hide"]) AND $_GET["hide"] !== "") {
		if(str_vorhanden($_GET["hide"], ",")) {
			$hide = array_unique(explode(",", $_GET["hide"]));
		} else {
			$hide = array($_GET["hide"]);
		}
	}
	// Filter erstellen
	if(isset($_GET["filter"]) AND $_GET["filter"] !== "") {
		$raw_filter = explode(";", $_GET["filter"]);
		// Filter sind vorhanden, wurden in array übergeben und werden nun verarbeitet
		foreach($raw_filter as $key => $value) {
			$filter[substr($value, 0, strpos($value, ":"))] = substr($value, strpos($value, ":")+1, strlen($value));
		}
	}	
	
	// Alle optionen bis hierhin festgelegt. Nun folgt das filtern:
	
	// Filter nach Name, oder Liste
	
	if(count($names) == 0) {
		if(!isset($_GET["name"]) OR $_GET["name"] == "") {
			$raw_result = $key_value_pairs;
		} else {
			foreach($key_value_pairs as $key => $value) {
				if($key_value_pairs[$key]["name"] == $_GET["name"]) {
					array_push($raw_result, $key_value_pairs[$key]);
				}
			} 
		}
	} else {
		if(count($names) >= 1 AND count($names) !== 0) {
			// Es handelt sich also um eine Liste. Folglich werden nur die Clients rausgegeben, welche 
			// den angegebenen Bedingungen entsprechen
				foreach($key_value_pairs as $key => $value) {
				if(in_array($key_value_pairs[$key]["name"], $names)) {
					// Daten sind vorhanden und werden an $raw_result angehängt
					array_push($raw_result, $key_value_pairs[$key]);
				}
			}
		}
	}
	
	// Ergebnisse wurden per Namen gefiltert. Nun folgen die restlichen Filter.
	$filtered_raw_result = kick_clients_by_properties($raw_result, $filter);
	
	
	// Ergenisse wurden gefiltert. Nun sind die Spalten dran.
	
	// Spalten, welche gezeigt werden sollen
	if(count($show) > 0) {
		foreach($filtered_raw_result as $key => $value) {
			foreach($value as $property => $prop_value) {
				if(!in_array($property, $show)) {
					unset($filtered_raw_result[$key][$property]);
				}
			}
		}
	}
	
	// Spalten, welche verborgen werden sollen
	if(count($hide) > 0) {
		foreach($filtered_raw_result as $key => $value) {
			foreach($value as $property => $prop_value) {
				if(in_array($property, $hide)) {
					unset($filtered_raw_result[$key][$property]);
				}
			}
		}
	}
	
	// Result generieren
	foreach($filtered_raw_result as $result_in) {
		array_push($result, $result_in);
	}	
	
	// Alles ist gefiltert - Nun geht es an die Ausgabe
	if(in_array($_GET["format"], $possible_output_format)) {
		switch($_GET["format"]) {
			case "json":
					print_r(json_encode($result));
			break;
			case "php":
					print_r($result);
			break;
			case "header":
					print_r(json_encode($header_dictionary));
			break;
			case "hAppy":
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
			break;
		}
	} else {
		echo "<strong>Error:</strong> You have chosen a non-supported output format!";
	}

?>
