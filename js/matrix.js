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
		tr.className = "line_"+input_obj[j]["name"]+" "+transformator.tr_class(input_obj[j]);
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
