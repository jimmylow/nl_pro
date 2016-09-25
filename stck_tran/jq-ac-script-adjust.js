$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#procomat1').autocomplete({
        source: 'item-data-adj.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#procomat1').val(ui.item.rm_code);
                    $itemrow.find('#procodesc1').val(ui.item.remark.replace(/&quot;/g, "\""));
                    $itemrow.find('#procouom1').val(ui.item.uom);
                    $itemrow.find('#procobal1').val(ui.item.mark);

                    // Give focus to the next input field to recieve input from user
                    $('#issueqtyid1').focus();

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
    var idprocomat     = "procomat"+rowCount;
    var idprocodesc   = "procodesc"+rowCount;
    var idprocouom     = "procouom"+rowCount;
    var idprocobal     = "procobal"+rowCount;
    var idissueqtyid = "issueqtyid"+rowCount;
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;"></td>',
            '<td><input name="procomat[]" id="'+idprocomat+'" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '+rowCount+')"></td>',
            '<td><input name="procodesc[]" id="'+idprocodesc+'" readonly="readonly" style="width: 303px;  border:0;" ></td>',
            '<td><input name="procouom[]" id="'+idprocouom+'" readonly="readonly" style=" border:0; width: 48px;"></td>',
               '<td><input name="procobal[]" id="'+idprocobal+'" readonly="readonly"  style="width: 75px; border-width: 0;text-align:right;"></td>',
            '<td><input name="issueqty[]" value="" class="tInput" id="'+idissueqtyid+'" onBlur="calcCost('+rowCount+');" style="width: 75px; text-align:right;"></td>',
        '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $procomat 	        = $row.find('#procomat'+rowCount);
        var $procodesc 	        = $row.find('#procodesc'+rowCount);
        var $procouom	        = $row.find('#procouom'+rowCount);
                var  $pbalqty                       =   $row.find('#procobal'+rowCount);
        var $issueqty	        = $row.find('#issueqtyid'+rowCount);

        if ( $('#procomat1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#procomat'+rowCount).autocomplete({
                source: 'item-data-adj.php',
                minLength: 0,
                select: function(event, ui) {
                    $procomat.val(ui.item.rm_code);
                    $procodesc.val(ui.item.remark.replace(/&quot;/g, '"'));
                  	$procouom.val(ui.item.uom);
                    $pbalqty .val(ui.item.mark);
	
                    // Give focus to the next input field to recieve input from user
                    $issueqty.focus();

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
            $($issueqty).focus();

        } // End if last itemCode input is empty
        return false;
    });
    
   
    $('#itemCode').focus(function(){
     //   window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });
    

}); // End DOM

	