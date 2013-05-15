//config for api calls
api_config = {
    /*
     * if you are calling from a different domain, you need to set up a proxy.
	 * this means, you need a url on your own server, which redirects the query (the part of the url behind the "?") to the actual api
	 *
	 * this is an example php file (one line):
	 *    <?php echo file_get_contents("http://adn-client-comparison.nigma.de/pull.php?".$_SERVER['QUERY_STRING']); ?>
	 *
	 * The url can be absolute or relative to the executing html file, but should not conain the domain name (see XMLHttpRequest)
	 *
	 */
	url: "api-1.1/array.php",
	
	/*
	 * This should contain accepted api params
	 */
	accepted_params: ["name","filter","show","hide","format"]

}



/*
 * arguments:
 *  params: {key:value,key:value} to pass as ?key=value&key=value}
 *
 *  successfn: function(res) which takes the query result as input
 *      if this is null, the function will be called synchronously, and the result will be returned as function result.
 *
 *
 */
function query_api(params,successfn) {
	var xmlhttp=new XMLHttpRequest();

	//filter arguments
	var param_str = "";
	for(i in api_config.accepted_params) {
		if(api_config.accepted_params[i] in params) {
			if(param_str != "") param_str = param_str+"&";
			param_str = param_str + api_config.accepted_params[i] + "=" + params[api_config.accepted_params[i]];
		}
	}

	var query_str = "?"+param_str;

	//determine if call is asynchronous or not
	var async=false;
	if(successfn)
		async=true;
		
	//if format not given, append format=json
	var query_complete = query_str;
	if(!("format" in params)) {
		if(query_complete="?")
			query_complete = "?format=json"
		else
			query_complete = query_complete + "&format=json";
	}
	
	console.log("querying API: "+query_complete);
	xmlhttp.open("GET",api_config.url+query_complete,async);

	//for async calls: make sure successfn will be executed afterwards
	if(async)
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
				successfn(JSON.parse(xmlhttp.responseText));
		};

	xmlhttp.send();
	
	//for sync calls: return result
	if(!async)
		return JSON.parse(xmlhttp.responseText);
}

//returns an Object, which's keys are the accepted column names, and values are human-readable translations.
function api_columns() {
	if("header_polled" in api_config) return api_config.header_polled

	api_config.header_polled = query_api({format:"header_inverse"});
	return api_config.header_polled;	
}


