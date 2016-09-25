$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#procomat1').autocomplete({
        source: 'item-data.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#procomat1').val(ui.item.rm_code);
                    $itemrow.find('#procodesc1').val(ui.item.remark);
                    $itemrow.find('#procouom1').val(ui.item.uom);
                    $itemrow.find('#procomark1').val(ui.item.mark);

                    // Give focus to the next input field to recieve input from user
                    $('#openingqtyid1').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.rm_code + " - " + item.uom + " - " + item.mark + " - " + item.remark + "</a>" )
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
    var idopeningqtyid  = "openingqtyid"+rowCount;
    
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" class="tInput" id="seqno" readonly="readonly" style="width: 27px; border:0;"></td>',
            '<td><input name="procomat[]" id="'+idprocomat+'" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)"></td>',
            '<td><input name="procodesc[]" id="'+idprocodesc+'" readonly="readonly" style="width: 303px;  border:0;" ></td>',
            '<td><input name="procouom[]" id="'+idprocouom+'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>',
            '<td><input name="procomark[]" id="'+idprocomark+'" readonly="readonly" style="width: 75px; border :0;"></td>',
            '<td><input name="issueqty[]" id="'+idopeningqtyid+'" onBlur="calcCost('+rowCount+');" style="width: 75px"></td>',

        '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $procomat 	        = $row.find('#procomat'+rowCount);
        var $procodesc 	        = $row.find('#procodesc'+rowCount);
        var $procouom	        = $row.find('#procouom'+rowCount);
        var $procomark	        = $row.find('#procomark'+rowCount);
        var $openingqtyid       = $row.find('#openingqtyid'+rowCount);

        if ( $('#procomat1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#procomat'+rowCount).autocomplete({
                source: 'item-data.php',
                minLength: 0,
                select: function(event, ui) {
                    $procomat.val(ui.item.rm_code);
                    $procodesc.val(ui.item.remark);
                  	$procouom.val(ui.item.uom);
                    $procomark.val(ui.item.mark);
	
                    // Give focus to the next input field to recieve input from user
                    $('#procomark'+rowCount).focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.rm_code + " - " + item.uom + " - " + item.mark + " - " + item.remark + "</a>" )
                    .appendTo( ul );
            };
          	
			// Add row after the first row in table
            $seqno.val(rowCount);
            
            $('.item-row:last', $itemsTable).after($row);
            $($procoucost).focus();

        } // End if last itemCode input is empty
        return false;
    });
    
   
    $('#itemCode').focus(function(){
     //   window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });
    

}); // End DOM

	