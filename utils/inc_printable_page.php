
    <img src="../images/print.gif" width="14" height="12" hspace="4" border="0" alt="" />
    <a href="#" class="smalllink4" onclick="printable(event, 'dataTable');">Printable Page</a>

<script type="text/javascript">
    function printable(event, table) {
        event.preventDefault();

        var title = "<?php echo $title ?>";
        var title2 = "<?php echo $title2 ?>";
        var title3 = "<?php echo $title3 ?>";

        var w = window.open("", "_blank");

        w.document.write("<html>")
        w.document.write("<link rel='stylesheet' type='text/css' href='../css/style.css'/>");
        w.document.write("<head>");
        w.document.write("<title><?php echo $title ?></title>");
        w.document.write("<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>");
        w.document.write("</head>");
        w.document.write("<body>");
        w.document.write("<table width='100%' border='0' bgcolor='white'>");
        w.document.write("<tr>");
        w.document.write("<td valign='top' align='center'>");

        w.document.write("<h3>");
        w.document.write(title);
        w.document.write("</h3>");
		w.document.write("<h4>"+title2);
        if (title3) {
            w.document.write("<br>"+title3);
        }
        w.document.write("</h4>");
		w.document.write("<h5>");
		w.document.write("Generated On <?php echo $generatedOn->format('d-m-Y H:i:s'); ?>");
        w.document.write("</h5>");

        //get the table
 	    var get = function(element) {
            if (!element.nodeType) {
                return document.getElementById(element);
            }
            return element;
        };

        table = get(table);
        $(table).find(".ignore_excel_export").remove();
        var table_content = "<table class='printable'>";

        //build the header
        var row = table.rows[0];
        table_content += "<tr>";
        for (var j = 0, col; col = row.cells[j]; j++) {
            table_content += "<th rowspan=" + col.rowSpan + " colspan=" + col.colSpan + " align=" + col.align + ">" + col.innerText + "</th>";
        }
        for (var i = 1, row; row = table.rows[i]; i++) {
            table_content += "<tr class='" + row.className + "'>";
            for (var j = 0, col; col = row.cells[j]; j++) {
                table_content += "<td rowspan=" + col.rowSpan + " colspan=" + col.colSpan + " align=" + col.align + ">" + col.innerText + "</td>";
            }
            table_content += "</tr>"
        }

        w.document.write("</table>");
        w.document.write(table_content);

        w.document.write("</td></tr></table></body></html>");
    }

</script>