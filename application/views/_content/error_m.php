	<h3>Error Encountered</h3>
	<div>
     <p style="color:#990000; margin:25px; font-size:15px; font-weight:bold;"><?php

   if(is_array($error)){
	    foreach($error as $err){
		echo $err . "<br />";
		}
    } else {
   		echo $error;
	}

	?>
	  	 <br />
	     <br />
	 <?php echo anchor($link, $link_name);?>	   

	
	</p>
   </div>


