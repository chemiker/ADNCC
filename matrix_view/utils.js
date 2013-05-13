//removes a string from an array
function array_remove(arr,str) {
	for (var i=arr.length-1; i>=0; i--) {
	    if (arr[i] == str) {
		        arr.splice(i, 1);
		}
	}
	return arr;
}

//removes array of strings from an array 
//input: complete list of strings, list of strings to remove
//output: list without certain strings
function array_remove_array(arr,arr_to_rem) {
	for (i in arr_to_rem)
		arr = array_remove(arr,arr_to_rem[i]);
	return arr;
}

//returns the GET query as object
//file.html?a=b&c=d --> {a:"b", c:"d"}
function get_params() {
	var prmstr = window.location.search.substr(1);
	var prmarr = prmstr.split ("&");
	var params = {};

	for ( var i = 0; i < prmarr.length; i++) {
		var tmparr = prmarr[i].split("=");
		params[tmparr[0]] = tmparr[1];
	}
	
	return params;
}