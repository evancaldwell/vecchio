	<br />
	<br />
	<h2><?php echo $message; ?></h2>
	<div>
     <p style=" margin:25px; font-size:15px; font-weight:bold;"><?php

   if(is_array($info)){
	    foreach($info as $err){
		echo $err . "<br />";
		}
    } else {
   		echo $info;
	}

	?>
	  	 <br />
	     <br />
	
	</p>
   </div>


