$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#fromqty1').autocomplete({
        source: 'item-price.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#fromqty1').val(ui.item.rm_code);
                    $itemrow.find('#procodesc').val(ui.item.remark);
                    $itemrow.find('#procouom').val(ui.item.uom);
                    $itemrow.find('#toqty1').val(ui.item.mark);
                    $itemrow.find('#procospre').val(ui.item.spread);
                    $itemrow.find('#prococut').val(ui.item.cut);
                    $itemrow.find('#procobund').val(ui.item.bundle);

                    // Give focus to the next input field to recieve input from user
                    $('#procoucost1').focus();

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
    var idfromqty = "fromqty"+rowCount;
    var idprocoucost = "procoucost"+rowCount;
    var idprice = "priceid"+rowCount;
    var idprococost = "prococost"+rowCount;
    var idtoqty = "toqty"+rowCount;
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" class="tInput" id="seqno" readonly="readonly" style="width: 27px;"></td>',
            '<td><input name="fromqty[]" value="" tProItem'+rowCount+'="1" id="'+idfromqty+'" class="tInput" style="width: 75px" onchange ="upperCase(this.id)"></td>',
            '<td><input name="toqty[]" value="" tMark="1" id="'+idtoqty+'"  class="tInput" style="width: 75px; "></td>',
            '<td><input name="price[]" value="" class="tInput" id="'+idprice+'" style="width: 75px" onBlur="disp_dec('+rowCount+');"></td>',

        '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $fromqty 	        = $row.find('#fromqty'+rowCount);
        var $procodesc 	        = $row.find('#procodesc');
        var $procouom	        = $row.find('#procouom');
        var $procoucost	        = $row.find('#procoucost'+rowCount);
        var $price	        = $row.find('#price');
        var $prococost	        = $row.find('#prococost');
        var $toqty	        = $row.find('#toqty'+rowCount);
        var $procospre	        = $row.find('#procospre');
        var $prococut	        = $row.find('#prococut');
        var $procobund	        = $row.find('#procobund');

        if ( $('#fromqty1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#fromqty'+rowCount).autocomplete({
                source: 'item-data.php',
                minLength: 0,
                select: function(event, ui) {
                    $fromqty.val(ui.item.rm_code);
                    $procodesc.val(ui.item.remark);
                  	$procouom.val(ui.item.uom);
                    $toqty.val(ui.item.mark);
                    $procospre.val(ui.item.spread);
                    $prococut.val(ui.item.cut);
                    $procobund.val(ui.item.bundle);
	
                    // Give focus to the next input field to recieve input from user
                    $procoucost.focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.rm_code + " - " + item.oum + " - " + item.size + " - " + item.remark + "</a>" )
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

	