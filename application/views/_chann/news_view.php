<!-- jQuery handles to place the header background images --> 
<div id="headerimgs_ns"> 
	<div id="news_window">
		<div id="news_box">
			<br />
			<h2>WELCOME TO VECCHIO TREES</h2>
			<h1>&nbsp;EVENTS</h1>
						
			<?php
			$count = count($event);
			if($count <= 2){
				echo "<h2>&nbsp;</h2>";
			}
			for($i=0;$i<$count;$i++){ ?>
			<h4 style="font-size:15px;"><?php echo $event[$i]['event_name'];?></h4>
			<h3 class="event_date"><?php echo $event[$i]['event_dates'];?></h3>
			<h3 class="event_site"><?php echo $event[$i]['event_web'];?></h3>			
		<?php 
			if($i != ($count -1)){
				echo "<h2>&bull;</h2>";
			}
		
		} ?>

		</div>
	</div>
</div>
