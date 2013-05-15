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

//returns an array (sorted) of columns
//TODO: is currently static - should be loaded dynamically
function getColumns() {
return ["name","platform","last_updated","minimum_os_version","known_active_development","multiple_accounts","global_stream","unified_stream","filter_stream_by_language","private_messages","public_patter_rooms","private_patter_rooms","browse_patter_rooms","open_patter_room_by_url","open_link_to_patter_room_in_app","broadcast_message","show_mentions_to_people_i_dont_follow","show_starred_posts","interactions_view","push_notifications","drafts","outbox","relative_time_stamps","absolute_time_stamps","username_completion","hashtag_completion","user_search","hashtag_search","save_hashtag_search","keyword_search","search_own_stream","block_user","report_post","hard_mute_user","soft_mute_user","mute_hashtag","mute_thread","mute_keyword","mute_client","hide_posts_seen_in_conversations","repost_from_another_account","quote_from_another_account","star_from_another_account","edit_profile","show_verification_status","accessible","language_annotations","light_theme","dark_theme","stream_marker_support","inline_media","inline_media_in_private_messages","creation_of_inline_links","file_api_integration","picture_services_integration","video_services_integration","read_later_services_integration","post_current_location","places_api","now_playing","url_shortening","full_screen_mode","open_links_from_stream_view","save_conversations","configurable_fonts","mail_post","twitter_crossposting","facebook_crossposting","buffer_integration","show_twitter_timeline","display_multiple_streams_at_once","landscape_view_of_streams","landscape_compose","text_expander_support","1password_integration","url_schemes","live_tile_support","known_limitations","supported_languages","developer_accounts","misc_notes","download_link"]
}



