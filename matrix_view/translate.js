// translation for the whole program:
// language codes: ISO 639-1
translation = {
	"name":{ en: "Name" },
	"platform":{ en: "Platform" },
	"last_updated":{ en: "Last Updated" },
	"minimum_os_version":{ en: "Minimum OS Version" },
	"known_active_development":{ en: "Known Active Development" },
	"multiple_accounts":{ en: "Multiple Accounts" },
	"global_stream":{ en: "Global Stream" },
	"unified_stream":{ en: "Unified Stream" },
	"filter_stream_by_language":{ en: "Filter Stream by Language" },
	"private_messages":{ en: "Private Messages" },
	"public_patter_rooms":{ en: "Public Patter Rooms" },
	"private_patter_rooms":{ en: "Private Patter Rooms" },
	"browse_patter_rooms":{ en: "Browse Patter Rooms" },
	"open_patter_room_by_url":{ en: "Open Patter Room by URL" },
	"open_link_to_patter_room_in_app":{ en: "Open Link to Patter Room in App" },
	"broadcast_message":{ en: "Broadcast Patter Message" },
	"show_mentions_to_people_i_dont_follow":{ en: "Show Mentions to People I don't follow" },
	"show_starred_posts":{ en: "Show Starred Posts" },
	"interactions_view":{ en: "Interactions View" },
	"push_notifications":{ en: "Push Notifications" },
	"drafts":{ en: "Drafts" },
	"outbox":{ en: "Outbox" },
	"relative_time_stamps":{ en: "Relative Time Stamps" },
	"absolute_time_stamps":{ en: "Absolute Time Stamps" },
	"username_completion":{ en: "Username Completion" },
	"hashtag_completion":{ en: "Hashtag Completion" },
	"user_search":{ en: "User Search" },
	"hashtag_search":{ en: "Hashtag Search" },
	"save_hashtag_search":{ en: "Save Hashtag Search" },
	"keyword_search":{ en: "Keyword Search" },
	"search_own_stream":{ en: "Search own Stream" },
	"block_user":{ en: "Block User" },
	"report_post":{ en: "Report Post" },
	"hard_mute_user":{ en: "Hard Mute User" },
	"soft_mute_user":{ en: "Soft Mute User" },
	"mute_hashtag":{ en: "Mute Hashtag" },
	"mute_thread":{ en: "Mute Thread" },
	"mute_keyword":{ en: "Mute Keyword" },
	"mute_client":{ en: "Mute Client" },
	"hide_posts_seen_in_conversations":{ en: "Hide Posts Seen in Conversation" },
	"repost_from_another_account":{ en: "Ropost From other Account" },
	"quote_from_another_account":{ en: "Quote From other Account" },
	"star_from_another_account":{ en: "Star From other Account" },
	"edit_profile":{ en: "Edit Profile" },
	"show_verification_status":{ en: "Show Verification Status" },
	"accessible":{ en: "Accessible" },
	"language_annotations":{ en: "Language Annotations" },
	"light_theme":{ en: "Light Theme" },
	"dark_theme":{ en: "Dark Theme" },
	"stream_marker_support":{ en: "Stream Marker Support" },
	"inline_media":{ en: "Inline Media" },
	"inline_media_in_private_messages":{ en: "Inline Media in Private Messages" },
	"creation_of_inline_links":{ en: "Creation of Inline Links" },
	"file_api_integration":{ en: "File API Integration" },
	"picture_services_integration":{ en: "Picture Services Integration" },
	"video_services_integration":{ en: "Video Services Integration" },
	"read_later_services_integration":{ en: "Read Later Services Integration" },
	"post_current_location":{ en: "Post Current Location" },
	"places_api":{ en: "Places API" },
	"now_playing":{ en: "Now Playing" },
	"url_shortening":{ en: "URL Shortening" },
	"full_screen_mode":{ en: "FUll Screen Mode" },
	"open_links_from_stream_view":{ en: "Open Links from Stream View" },
	"save_conversations":{ en: "Save Conversations" },
	"configurable_fonts":{ en: "Configurable Fonts" },
	"mail_post":{ en: "Mail Post" },
	"twitter_crossposting":{ en: "Twitter Crossposting" },
	"facebook_crossposting":{ en: "Facebook Crossposting" },
	"buffer_integration":{ en: "Buffer Integration" },
	"show_twitter_timeline":{ en: "Show Twitter Timeline" },
	"display_multiple_streams_at_once":{ en: "Display Multiple Streams at Once" },
	"landscape_view_of_streams":{ en: "Landscape View of Streams" },
	"landscape_compose":{ en: "Landscape Compose" },
	"text_expander_support":{ en: "Text Expander Support" },
	"1password_integration":{ en: "One Password Integration" },
	"url_schemes":{ en: "URL schemes" },
	"live_tile_support":{ en: "Live Tile Support" },
	"supported_languages":{ en: "Supported Languages" },
	"developer_accounts":{ en: "Developer Accounts" },
	"misc_notes":{ en: "Misc. Notes" },
	"download_link":{ en:  "Download" }
}

/* translate internal variable
 * 
 * input:
 * 	str: variable to translate
 * 	lang: target language
 *  lang_fallback: if translation for lang not available, use this language
 * 					(if still unsuccessful, str will be ruturned raw)
 */
function translate(str,lang,lang_fallback) {
	if(str in translation) {
		if(lang in translation[str])
			return translation[str][lang]
		else if (lang_fallback in translation[str]) {
			console.log("translate: unable to translate "+str+" to "+lang+", but successful to "+lang_fallback);
			return translation[str][lang_fallback]
		} else {
			console.log("translate: unable to translate "+str);
			return str;
		}
	}
	else {
		console.log("translate: unable to translate "+str);
		return str;
	}
}

function detect_language() {
	var lang = navigator.language || window.navigator.language;
	if (lang) return lang.substring(0,2);
	return "en";
}

function translate_auto_en(str) { return translate(detect_language(),"en")}
