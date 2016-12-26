<script type="text/javascript"> 
$(document).ready(function(){
	var ac_config = {
		source: "../bom_tran/autocomscrpro1.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
			//$("#promodesc").val(ui.item.prod_desc);
			//$("#totallabcid").val(ui.item.prod_labcst);
	
		},
		minLength:1
		
	};
	$("#prod_code").autocomplete(ac_config);
});
</script>