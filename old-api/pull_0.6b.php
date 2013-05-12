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

// Doing $_GET magic to get an idea about the query
foreach(array_flip($_GET) as $call) {
	if(in_array($call, $header_dictionary)) {
		array_push($anfrage, $_GET[$call]);
	}
}

if($_GET !== array()) {
// Transforming the query
$anfrage = array_combine(array_flip($_GET), $anfrage);
$anfrage_flip = array_flip($anfrage);

// Hilfsauflistung bauen
foreach($key_value_pairs as $entry) {
	array_push($hilfsauflistung, $entry["name"]);
}

function deleteObjWithProperty($search,$arr)
  {
  foreach ($arr as &$val)
    {
    if (array_search($search,$val)!==false)
      {
      unlink($val);
      }
    }
  return $arr;
  }

$i = 0;
$u = 0;
$result_raw = array();
$result = array();
$delete = array();

foreach($anfrage as $url) {
	if ($i == 0) {
		foreach($key_value_pairs as $data) {
			if($_GET[$anfrage_flip[$url]] == $data[$anfrage_flip[$url]]) {
				array_push($result_raw, $data);
			}
		}
	}
	if ($i > 0) {
		foreach($result_raw as $client) {
			while($u <= count($result_raw)) {
					if($result_raw[$u][$anfrage_flip[$url]] !== $url) {
						array_push($delete, $result_raw[$u]["name"]);
					}
					$u++;
			}
		}
	}
	$i++;
	$u = 0;
}

// delete  mit result_raw abgleichen

foreach($result_raw as $client) {
	if(in_array($client["name"], $delete)) {
	} else {
		array_push($result, $client);
	}
}
}

if($_GET == array()) {
	echo json_encode($key_value_pairs);
} else {
	echo json_encode($result);
}
?>