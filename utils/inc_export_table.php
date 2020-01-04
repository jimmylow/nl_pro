		<img src='../images/export.png' width='16px'>
		<a href="#" class="smalllink4" onclick="return KendoExportExcel(event, 'dataTable', 'Sheet Name Here', 'exportExcel.xlsx');">
		Export xlsx</a>

<script type="text/javascript">
var KendoExportExcel = function(event, table, name, filename) {
	event.preventDefault();
	
	//get the table
	var get = function(element) {
        if (!element.nodeType) {
            return document.getElementById(element);
        }
        return element;
	};
	
	// convert string to number
	var convert = function(s, align) {
		if (align == "left" || align == "" ) return s;
	    var s = s.replace(/\,/g,'');
	    var expression = /^\([\d,\.]*\)$/;
    	if(s.match(expression)){
	        //It matched - strip out parentheses and append - at front
	        s = '-' + s.replace(/[\(\)]/g,'');
	    }
   
	    if (!isNaN(parseFloat(s)) && isFinite(s)) {
	    	// replace bracket with negative 
	    	
	    	if (!isNaN(parseFloat(s)))
		        s = parseFloat(s)
	    }
	    return s;
	}
	
	table = get(table);	
	$(table).find(".ignore_excel_export").remove(); 
	
    var workbook = {
        creator: "developer",
        sheets:[]
    };

	//build the header
    var columns=[];
    var cells=[];
    var row = table.rows[0];
    for (var j = 0, col; col = row.cells[j]; j++) {
        columns.push({ autoWidth: true });
        cells.push({ value: col.innerText, rowSpan: col.rowSpan, colSpan: col.colSpan, textAlign: col.align })
    }
    excelRows =[
        {
            cells: cells
        }
    ];

	//put the content
    for (var i = 1, row; row = table.rows[i]; i++) {
        cells=[];
        for (var j = 0, col; col = row.cells[j]; j++) {
			cells.push({ value: convert(col.innerText, col.align), rowSpan: col.rowSpan, colSpan: col.colSpan, textAlign: col.align, wrap: "true" })
        }
        excelRows.push({ cells: cells });
    }

    //export to Excel
    sheet={
        title: name,
        columns: columns,
        //freezePane: { colSplit: 2, rowSplit: 1 },
		//filter: { from: 0, to: 7 },
	};
	
    sheet.rows=excelRows;
    workbook.sheets.push(sheet);
    var dataURL = new kendo.ooxml.Workbook(workbook).toDataURL();
    
    // save the workbook
    kendo.saveAs({
        dataURI: dataURL,
        fileName: filename
    });
}
</script>