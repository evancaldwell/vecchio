<script type="text/javascript">
$(function() {
	
	function log(message) {
		
		$.ajax({
        	url: '<?php echo site_url('search/by_id');?>',
        	data: "term=" + message,
			type: 'POST',
        	dataType: "json",
        	success: function(data){
	            // .text(html) 
	            var new_order = '<?php echo site_url('vecchio_admin/new_shipping/');  ?>';
				$("<li><a href=\""+ new_order + "/" + data.id +"\" >"+data.label+"</a></li>").prependTo("#log");
				// $("#log").attr("scrollTop", 0);
		        $("#cond_picker").val("");
        	}
			
    	});

	}
	
	$("#ajax_search").autocomplete({
            minLength: 1,
            source: function(req, add){
                $.ajax({
                    url: '<?php echo site_url('search');?>',
                    dataType: 'json',
                    type: 'POST',
                    data: req,
                    success: function(data){
                        if(data.response =='true'){
                           add(data.message);
                        }
                    }
                });
            },
            select: function(event, ui){
              log(ui.item ? (ui.item.id) : "No Selection");
			}
        });
});
</script>
<div>
			<h3>Start New Order</h3>
	<div class="half">
			<h3>Search For Customer By Name</h3>
<input id="ajax_search" type="text" class="ajax_search_inp"/>
    </div>
    <div class="half">
	        <h3>Search Results</h3>
<ul id="log" class="ajax_search_ul" >
	<li><?php echo anchor('vecchio_admin/add_user_form','Create New Customer Account &raquo;');?> </li>
</ul>
	</div>
		<div class="clearup"></div>
    <br />
    <br />
    <br />
</div>
