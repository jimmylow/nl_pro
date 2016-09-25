$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#bkitmcd1').autocomplete({
        source: 'item-data-bk.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#bkitmcd1').val(ui.item.rm_code);
                    $itemrow.find('#bkitmdesc1').val(ui.item.desc);
                    $itemrow.find('#itmavai1').val(ui.item.avail);
                    $itemrow.find('#itmuom1').val(ui.item.uom);

                    // Give focus to the next input field to recieve input from user
                    $('#bkqty1').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.rm_code + "- " + item.desc + "- " + item.avail + "- "+ item.uom +"</a>" )
            .appendTo( ul );
    };


    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');
   
    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){
    	var table = document.getElementById('itemsTable');
	    var rowCount = table.rows.length; 

    	 // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var idbkitmcd   = "bkitmcd"+rowCount;
    var idbkitmdesc = "bkitmdesc"+rowCount;
    var iditmavai1  = "itmavai"+rowCount;    
    var iditmuom    = "itmuom"+rowCount;
    var idbkqty     = "bkqty"+rowCount;
    
    var rowTemp = [
        '<tr class="item-row">',
        '<td style="width: 30px"><input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;"></td>',
        '<td><input name="bkitmcd[]" id="'+idbkitmcd+'" class="autosearch" style="width: 150px" onchange ="upperCase(this.id)" onblur="getitmdesc(this.value, '+rowCount+')"></td>',
        '<td><input name="bkitmdesc[]" id="'+idbkitmdesc+'" readonly="readonly" style="width: 300px; border:0;"></td>',
        '<td><input name="itmavai[]" id="'+iditmavai1+'" style="width: 100px; border:0; text-align:right" readonly="readonly"></td>',
        '<td><input name="itmuom[]" id="'+iditmuom+'" readonly="readonly" style="width: 75px; border:0"></td>',
        '<td><input name="bkqty[]" id="'+idbkqty+'" style="width: 100px; text-align:right" onblur="chkdecval(this.value, '+rowCount+')"></td>',
        '</tr>'
    ].join('');
	
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno     = $row.find('#seqno');
        var $bkitmcd   = $row.find('#bkitmcd'+rowCount);
        var $bkitmdesc = $row.find('#bkitmdesc'+rowCount);
        var $itmavai1  = $row.find('#itmavai1'+rowCount);
        var $itmuom    = $row.find('#itmuom'+rowCount);
        var $bkqty	   = $row.find('#bkqty'+rowCount);        

        if ( $('#bkitmcd1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#bkitmcd'+rowCount).autocomplete({
                source: 'item-data-bk.php',
                minLength: 0,
                select: function(event, ui) {
                    $bkitmcd.val(ui.item.rm_code);
                    $bkitmdesc.val(ui.item.desc);
                  	$itmavai1.val(ui.item.avail);
                  	$itmuom.val(ui.item.uom);

                    // Give focus to the next input field to recieve input from user
                    $('#bkqty'+rowCount).focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.rm_code + "- " + item.desc + "- " + item.avail + "- "+ item.uom + "</a>" )
                    .appendTo( ul );
            };
          	
			// Add row after the first row in table
            $seqno.val(rowCount);
            
            $('.item-row:last', $itemsTable).after($row);
            $($bkitmcd).focus();

        } // End if last itemCode input is empty
        return false;
    });
    
   
    $('#itemCode').focus(function(){
     //   window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });
    

}); // End DOM

	