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
		case "name":
			if(("download_link" in obj) && obj.download_link)
		    	return '<a href="'+obj["download_link"]+'">'+obj["name"]+'</a>'
			else
				return obj["name"];
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



transformator = {
	cell: transform_cell,
	header: translate_en,
	tr_class: tr_classes,
	td_class: td_classes,
	columns_to_remove: ["download_link"]
}
