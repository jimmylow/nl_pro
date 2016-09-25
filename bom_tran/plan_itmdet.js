$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#planmat1').autocomplete({
        source: 'item-data.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#planmat1').val(ui.item.rm_code);
                    $itemrow.find('#plandes1').val(ui.item.remark);
                    $itemrow.find('#planuom1').val(ui.item.uom);
                    $itemrow.find('#plitmava1').val(ui.item.avail);
                    
                    // Give focus to the next input field to recieve input from user
                    $('#planuco1').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.rm_code + " - " + item.colour + " - " + item.size + " - " + item.remark + "</a>" )
            .appendTo( ul );
    };


    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');
   
    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){
    	var table = document.getElementById('itemsTable');
	    var rowCount = table.rows.length; 

    	 // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var idplanmat = "planmat"+rowCount;
    var idplandes = "plandes"+rowCount;
    var idplanuom = "planuom"+rowCount;
    var idplantco = "plantco"+rowCount;
    var idplitmava = "plitmava"+rowCount;
    var idplanpur = "plantpur"+rowCount;
    var idplanbok = "planbok"+rowCount;
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" id="seqno" readonly="readonly" style="width: 15px; border:0;"></td>',
            '<td><input name="planmat[]" id="'+idplanmat+'" class="autosearch" style="width: 100px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '+rowCount+')"></td>',
            '<td><input name="plandes[]" id="'+idplandes+'" readonly="readonly" style="border: 0; width: 230px;"></td>',
            '<td><input name="planuom[]" id="'+idplanuom+'" readonly="readonly" style="border: 0; width: 40px;"></td>',
            '<td><input name="plantco[]" id="'+idplantco+'" style="width: 90px; text-align:right;" onBlur="calccomps('+rowCount+');"></td>', 
            '<td><input name="plitmava[]" readonly="readonly" id="'+idplitmava+'" style="width: 90px;border:0;text-align:right;"></td>',
            '<td align="center"><input name="planpur[]" type="checkbox" id="'+idplanpur+'" style="width: 90px; border:0;" onclick="mycheckidpur(this, '+idplanmat+');"></td>',
            '<td><input name="planbok[]" id="'+idplanbok+'" style="width: 90px; text-align:right;" onblur="chkdecval(this.value, '+rowCount+')"></td>',
		'</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno     = $row.find('#seqno');
        var $prplanmat = $row.find('#planmat'+rowCount);
        var $prplandes = $row.find('#plandes'+rowCount);
        var $prplanuom = $row.find('#planuom'+rowCount);
        var $prplantco = $row.find('#plantco'+rowCount);
        var $prplitmava = $row.find('#plitmava'+rowCount);
        var $prplanpur = $row.find('#planpur'+rowCount);
        var $prplanbok = $row.find('#planbok'+rowCount);
       
        if ( $('#planmat:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#planmat'+rowCount).autocomplete({
                source: 'item-data.php',
                minLength: 0,
                select: function(event, ui) {
                    $prplanmat.val(ui.item.rm_code);
                    $prplandes.val(ui.item.remark);
                  	$prplanuom.val(ui.item.uom);
                  	$prplitmava.val(ui.item.avail);
                    	
                    // Give focus to the next input field to recieve input from user
                    $('#plantco'+rowCount).focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.rm_code + " - " + item.colour + " - " + item.size + " - " + item.remark + "</a>" )
                    .appendTo( ul );
            };
          	
			// Add row after the first row in table
			
            $seqno.val(rowCount);
             
            $('.item-row:last', $itemsTable).after($row);
           

        } // End if last itemCode input is empty
        return false;
    });
    
   
    $('#itemCode').focus(function(){
     //   window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });
    

}); // End DOM

	