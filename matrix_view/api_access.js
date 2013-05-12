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
	url: "../pull.php"
}



/*
 * arguments:
 *  query: query to be passed to adncc api
 *
 *  successfn: function(res) which takes the query result as input
 *      if this is null, the function will be called synchronously, and the result will be returned as function result.
 *
 *
 */
function query_api(query,successfn) {
	var xmlhttp=new XMLHttpRequest();

	//make sure query works
	if(query) {
		if (query[0]!='?') query = "?"+query;
	} else {
		query = ""
	}

	//determine if call is asynchronous or not
	var async=false;
	if(successfn)
		async=true;

	xmlhttp.open("GET",api_config.url+query,async);

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
return ["name","platform","last_updated","minimum_os_version","known_active_development","multiple_accounts","global_stream","unified_stream","filter_stream_by_language","private_messages","public_patter_rooms","private_patter_rooms","browse_patter_rooms","open_patter_room_by_url","open_link_to_patter_room_in_app","broadcast_message","show_mentions_to_people_i_dont_follow","show_starred_posts","interactions_view","push_notifications","drafts","outbox","relative_time_stamps","absolute_time_stamps","username_completion","hashtag_completion","user_search","hashtag_search","save_hashtag_search","keyword_search","search_own_stream","block_user","report_post","hard_mute_user","soft_mute_user","mute_hashtag","mute_thread","mute_keyword","mute_client","hide_posts_seen_in_conversations","repost_from_another_account","quote_from_another_account","star_from_another_account","edit_profile","show_verification_status","accessible","language_annotations","light_theme","dark_theme","stream_marker_support","inline_media","inline_media_in_private_messages","creation_of_inline_links","file_api_integration","picture_services_integration","video_services_integration","read_later_services_integration","post_current_location","places_api","now_playing","url_shortening","full_screen_mode","open_links_from_stream_view","save_conversations","configurable_fonts","mail_post","twitter_crossposting","facebook_crossposting","buffer_integration","show_twitter_timeline","display_multiple_streams_at_once","landscape_view_of_streams","landscape_compose","text_expander_support","1password_integration","url_schemes","live_tile_support","supported_languages","developer_accounts","misc_notes","download_link"]
}



/*
 *generate an html table object from api output:
 *
 * input_obj: output of query_api
 *
 * columns: array (sorted) of columns to show
 * 
 * transformator_inp = {
 *  	cell: function(string,obj),     //output: what to put in cell
 *  	header: function(string),       //output: what to put in table header (column names)
 *  	tr_class: function(obj)         //output: classes to add to tr
 * 		td_class: function(string,obj), //output: classes to add to tr
 *                                      //inputs: 
 *											string: column name, 
 *											obj: row data
 *										//access cell content as obj[string]
 *
 *		columns_to_remove: [String]	    //list of columns which don't need to be shown (ex.: a transformator which puts download_link in name)
 *
 *										// if a function/value is null or undefined, the raw data will be used.
 *  }
 *
 */
function createTable(input_obj,columns_inp,transformator_inp) {

	//read transformator from input
	var transformator = {
		cell : transformator_inp.cell || function(string,obj){return obj[string];},
		header:transformator_inp.header||function(string){return string;},
		tr_class : transformator_inp.tr_class || function(obj){return ""},
		td_class : transformator_inp.td_class || function(obj){return ""},
		columns_to_remove: transformator_inp.columns_to_remove || []
	};
	var columns = array_remove_array(columns_inp,transformator.columns_to_remove);



	var table = document.createElement("table");

	//create header
	var tr = document.createElement("tr");
	tr.id = "line_header";
	for(i in columns) {
		var h = document.createElement("th");
		var str = columns[i];
		str = transformator.header(str);
		h.innerHTML = str;
		h.className = "column_"+columns[i];
		tr.appendChild(h);
	}
	table.appendChild(tr);

	//create body
	for (j in input_obj) {
		var tr = document.createElement("tr");
		tr.id = "line_"+input_obj[j]["name"];
		tr.className = "line_"+input_obj[j]["name"]+" "+transformator.header(input_obj[j]);
		for (i in columns) {
			var d = document.createElement("td");
			var str = input_obj[j][columns[i]];
			var raw_str = str; //needed later for YES/NO detection
			str = transformator.cell(columns[i],input_obj[j])
			d.innerHTML = str;
			d.className = "column_"+columns[i]+" line_"+input_obj[j]["name"]+" "+transformator.td_class(columns[i],input_obj[j]);
			tr.appendChild(d);
		}
		table.appendChild(tr);
	}
	return table;
}
