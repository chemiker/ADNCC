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

