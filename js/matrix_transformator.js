//cell transformation for createTable
function transform_cell(column_name,obj) {
	var res = transform_cell_individual(column_name,obj)
	//if(res.substring(0,3)=="YES")
	//	res=res.substring(3)
	//else if (res.substring(0,2)=="NO")
	//	res=res.substring(2);
	return res;
}
function transform_cell_individual(column_name,obj) {
	switch(column_name) {
		
		//name: add download link to name cell
		case "name":
			if(("download_link" in obj) && obj.download_link)
		    	return '<a href="'+obj["download_link"]+'">'+obj["name"]+'</a>'
			else
				return obj["name"];
			break;
			
		//dev_account: add links to profile pages
		case "developer_accounts":
			if(("developer_accounts" in obj) && obj.developer_accounts) {
				var devaccs = obj.developer_accounts.replace(/\s/g, '').replace(/\n/g, '').replace(/@/g,'').split(','); //remove whitespace, remove the @, and split by ','
				var res = ""
				for (i in devaccs)
					if (devaccs[i] != "")
						res = res + ' <a href="https://alpha.app.net/'+devaccs[i]+'">@'+devaccs[i]+'</a>';
				return res;
			} else
				return obj["developer_accounts"]
			break;
		
		default:
			return obj[column_name];
	}
}




//add tr classes
function tr_classes(obj) {
	return "";
}



//add td classes
function td_classes(string,obj) {
	var raw_str = obj[string];
	var res = "";
	if(string != "name") {
		if(raw_str.substring(0,3).toUpperCase() == "YES")
			res = res+" YES"
		else if (raw_str.substring(0,2).toUpperCase() == "NO")
			res = res+" NO"
	}
	return res;
}



function transform_header(str) {
	if (str in api_columns())
		return api_columns()[str]
	else
		return str;
}

transformator = {
	cell: transform_cell,
	header: transform_header,
	tr_class: tr_classes,
	td_class: td_classes,
	columns_to_remove: ["download_link"]
}
