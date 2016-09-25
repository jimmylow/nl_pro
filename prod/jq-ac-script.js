$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#procomat1').autocomplete({
        source: 'item-data.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
            
                    // Populate the input fields from the returned values
                    $itemrow.find('#procomat1').val(ui.item.rm_code);
                    $itemrow.find('#procodesc1').val(ui.item.remark.replace(/&quot;/g, "\""));
                    $itemrow.find('#procouom1').val(ui.item.uom);
                    $itemrow.find('#procomark1').val(ui.item.mark);
                    $itemrow.find('#procospre1').val(ui.item.spread);
                    $itemrow.find('#prococut1').val(ui.item.cut);
                    $itemrow.find('#procobund1').val(ui.item.bundle);
                    $itemrow.find('#prococost1').val(ui.item.cost);

                    // Give focus to the next input field to recieve input from user
                    $('#procoucost1').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.rm_code + " - " + item.colour + " - " + item.size + " - " + item.remark + " - " + item.cost + "</a>" )
            .appendTo( ul );
    };


    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');

   
    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){
    	var table = document.getElementById('itemsTable');
	    var rowCount = table.rows.length; 

    	 // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var idprocomat = "procomat"+rowCount;
    var idprocodesc = "procodesc"+rowCount;
    var idprocouom = "procouom"+rowCount;
    var idprocoucost = "procoucost"+rowCount;
    var idprococompt = "prococompt"+rowCount; 
    var idprococost = "prococost"+rowCount;
    var idprocomark = "procomark"+rowCount;
    var idprocospre = "procospre"+rowCount;
    var idprococut = "prococut"+rowCount;
    var idprocobund = "procobund"+rowCount;
  
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" class="tInput" id="seqno" readonly="readonly" style="width: 27px; border:0;"></td>',
            '<td><input name="procomat[]" value="" tProItem'+rowCount+'="1" id="'+idprocomat+'" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '+rowCount+')"></td>',
            '<td><input name="procodesc[]" value="" class="tInput" id="'+idprocodesc+'" style="width: 303px;" onchange ="upperCase(this.id)"></td>',
            '<td><input name="procouom[]" value="" class="tInput"  id="'+idprocouom+'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>',
            '<td><input name="procoucost[]" value="" class="tInput" id="'+idprocoucost+'" onBlur="calcCost('+rowCount+');" style="width: 75px; text-align:right;"></td>',
            '<td><input name="prococompt[]" value="" class="tInput" id="'+idprococompt+'" onblur="checkcompsum('+rowCount+')" onkeyup="calcCost('+rowCount+');" style="width: 75px; text-align:right;"></td>',
            '<td><input name="prococost[]" value="" class="tInput" id="'+idprococost+'" readonly="readonly" style="width: 75px; border:0; text-align:right;" ></td>',
            '<td><input name="procomark[]" value="" tMark="1" id="'+idprocomark+'" readonly="readonly" style="width: 75px; border:0; text-align:right;"></td>',
            '<td><input name="procospre[]" value="" tSpre="1" id="'+idprocospre+'" readonly="readonly" style="width: 75px; border:0; text-align:right;"></td>',
            '<td><input name="prococut[]" value="" tCut="1" id="'+idprococut+'" readonly="readonly" style="width: 75px;  border:0; text-align:right;"></td>',
            '<td><input name="procobund[]" value="" tBund="1" id="'+idprocobund+'" readonly="readonly" style="width: 75px;  border:0; text-align:right;"></td>',
        '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $procomat 	        = $row.find('#procomat'+rowCount);
        var $procodesc 	        = $row.find('#procodesc'+rowCount);
        var $procouom	        = $row.find('#procouom'+rowCount);
        var $procoucost	        = $row.find('#procoucost'+rowCount);
        var $prococompt	        = $row.find('#prococompt'+rowCount);
        var $prococost	        = $row.find('#prococost'+rowCount);
        var $procomark	        = $row.find('#procomark'+rowCount);
        var $procospre	        = $row.find('#procospre'+rowCount);
        var $prococut	        = $row.find('#prococut'+rowCount);
        var $procobund	        = $row.find('#procobund'+rowCount);

        if ( $('#procomat1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#procomat'+rowCount).autocomplete({
                source: 'item-data.php',
                minLength: 0,
                select: function(event, ui) {
                	
                    $procomat.val(ui.item.rm_code);
                    $procodesc.val(ui.item.remark.replace(/&quot;/g, '"'));
                  	$procouom.val(ui.item.uom);
                    $procomark.val(ui.item.mark);
                    $procospre.val(ui.item.spread);
                    $prococut.val(ui.item.cut);
                    $procobund.val(ui.item.bundle);
                    $prococost.val(ui.item.cost);
				
                    // Give focus to the next input field to recieve input from user
                    $procoucost.focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.rm_code + " - " + item.colour + " - " + item.size + " - " + item.remark + " - " + item.cost + "</a>" )
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

	