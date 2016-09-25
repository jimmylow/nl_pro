$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#procomat1').autocomplete({
        source: 'item-data-opening.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#procomat1').val(ui.item.prod_code);
                    $itemrow.find('#procodesc1').val(ui.item.remark);
                    $itemrow.find('#procouom1').val(ui.item.uom);
                    $itemrow.find('#openingcostid1').val(ui.item.openingcost);

                    // Give focus to the next input field to recieve input from user
                    $('#procomark1').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.prod_code + " - " + item.uom + " - " + item.remark + " - " + item.openingcost + "</a>" )
            .appendTo( ul );
    };


    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');

   
    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){
    	var table = document.getElementById('itemsTable');
	    var rowCount = table.rows.length; 

    	 // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var idprocomat  = "procomat"+rowCount;
    var idprocodesc = "procodesc"+rowCount;
    var idprocouom  = "procouom"+rowCount;
    var idprocomark = "procomark"+rowCount;
    var idissueqty  = "openingqtyid"+rowCount;
    var idopeningcost  = "openingcostid"+rowCount
    
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;"></td>',
            '<td><input name="procomat[]" id="'+idprocomat+'" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '+rowCount+'")></td>',
            '<td><input name="procodesc[]" id="'+idprocodesc+'" readonly="readonly" style="width: 303px;  border:0;" ></td>',
            '<td><input name="procouom[]" id="'+idprocouom+'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>',
            '<td><input name="procomark[]" id="'+idprocomark+'"  onBlur="calcCost('+rowCount+');" style="width: 75px;" onchange ="upperCase(this.id)"></td>',
            '<td><input name="openingqty[]" id="'+idissueqty+'" onBlur="calcCost('+rowCount+');" style="width: 75px"></td>',
            '<td><input name="openingcost[]" id="'+idopeningcost+'" onBlur="calcCost('+rowCount+');" style="width: 75px"></td>',
        '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $procomat 	        = $row.find('#procomat'+rowCount);
        var $procodesc 	        = $row.find('#procodesc'+rowCount);
        var $procouom	        = $row.find('#procouom'+rowCount);
        var $procomark	        = $row.find('#procomark'+rowCount);
        var $issueqty	        = $row.find('#openingqtyid'+rowCount);
        var $openingcostid	        = $row.find('#openingcostid'+rowCount);
        

        if ( $('#procomat1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#procomat'+rowCount).autocomplete({
                source: 'item-data-opening.php',
                minLength: 0,
                select: function(event, ui) {
                    $procomat.val(ui.item.prod_code);
                    $procodesc.val(ui.item.remark);
                  	$procouom.val(ui.item.uom);
                  	$openingcostid.val(ui.item.openingcost);
                  	
                 		
                    // Give focus to the next input field to recieve input from user
                    $('#procomark'+rowCount).focus();

                    return false;
                }

            }).data( "autocomplete" )._renderItem = function( ul, item ) {
        		return $( "<li></li>" )
        	    .data( "item.autocomplete", item )
        	    .append( "<a>" + item.prod_code + " - " + item.uom + " - " + item.remark + " - " + item.openingcost + "</a>" )
        	    .appendTo( ul );
    		};
          	
			// Add row after the first row in table
            $seqno.val(rowCount);
            
            $('.item-row:last', $itemsTable).after($row);
            $($procomat).focus();

        } // End if last itemCode input is empty
        return false;

    });

}); // End DOM

	