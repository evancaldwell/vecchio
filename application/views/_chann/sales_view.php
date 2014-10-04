<script type="text/javascript" src="<?php echo base_url();?>_js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>_js/fancybox/jquery.fancybox-1.3.4.css" 

media="screen" />
<script type="text/javascript" id="sourcecode"> 
	$(function()
	{
		$('#spec_txt').jScrollPane();
			$(".sales_bio").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});
	});
</script>
<!-- jQuery handles to place the header background images --> 
<div id="headerimgs_ns"> 

	<div id="contact_window">
		<div id="spec_txt" >
			<table>
				<?php
				$call = $this->uri->segment(3);
				 if($call){ ?>
				<tr> 
	            <td colspan="2"><h3 style="text-align:left; padding-left:0px; margin-top:10px;">
	            Please Contact Your Local Sales Rep Regarding <?php echo ucfirst(str_replace('~', ' ', $call));?> 

Sales:
	            </h3></td> 
	             </tr>
				<?php } else { ?>
				<tr>
					<td colspan="2"><h3 style="text-align:left; padding-left:0px; margin-top:10px;">Corporate Office</h3></td>
				</tr>
				<tr>
					<td><span class="large">VECCHIO Trees<br/>4285 Spyres Way<br/>Modesto, CA 95356</span></td>
					<td><span class="large">T: 855/819-7777<br/>F: 855/828-6708</span></td>
				</tr>   
				<?php } ?>


				  <tr> 
                	<td colspan="2">
						<h3 style="text-align:left; padding-left:0px; margin-top:10px;">California Sales Reps</h3>
					</td> 
                </tr>
				<tr>
					<td colspan="2"><strong>Gretchen McCauley</strong><br/><em>Southern California Sales Rep - 
						<a class="sales_bio" href="#gretchen">View Bio &raquo;</a></em><br/>949/274-5223<br/>
				<?php echo mailto('gretchen@vecchiotrees.com'); ?><br />
							
							<div style="display:none;">
							<div style="width:600px;" id="gretchen" ><br />
							<strong>Gretchen McCauley</strong><br/><em>Southern California Sales Rep</em><br/>949/274-5223<br/>
					<?php echo mailto('gretchen@vecchiotrees.com'); ?><br />
							<p style="float:left; ">Gretchen McCauley, Sales in Southern 

California, at Vecchio Trees has been in the landscape and nursery industry for 15 years. She started out in the field 

with hands on training, learning the procurement of large specimen trees. Her knowledge of Olive trees largely 

surpasses ones expectations. Through the years she supervised and managed several large landscape projects throughout 

the state. Gretchen specializes in Landscape photography. Her work is featured in California Home magazines and 

websites, affiliated with advertising campaigns. Her eye for architectural beauty aids her in creating elegance from 

the simplicity of our natural surroundings.</p>
							</div><br /><br />
							</div><!-- end hide div -->
							</td>
						
						<tr>
		                 <td colspan="2"><strong>Julia Hillier</strong><br/><em>San Diego Sales Rep</em><br />
							Cell: 760/214-1008<br />			
							<?php echo mailto('julia@vecchiotrees.com'); ?><br />
								
						</td> 
		                 </tr>
						<tr>
		                 <td colspan="2"><strong>Adam Gunn</strong><br/><em>Director of Sales 
</em><br />
							Cell: 949/439-1202<br />			
							<?php echo mailto('adam@vecchiotrees.com'); ?><br />

						</td> 
		                 </tr>
						<tr>
		                 <td colspan="2"><strong>John P. Stryker</strong><br/><em>Southern California Sales 

Rep</em><br />
							Cell: 714/343-6975<br />			
							<?php echo mailto('johns@vecchiotrees.com'); ?><br />
						 </td>
						</tr>

				<tr>
					<td colspan="2"><strong>Michelle Stacey</strong><br/><em>Northern California Sales Rep</em><br />
								Cell: 559/802-6336<br />
								Phone: 559/528-9926<br />
								<?php echo mailto('michelle@vecchiotrees.com'); ?><br/>
					</td>
				</tr>



				<tr>
					<td colspan="2"><h3 style="text-align:left; padding-left:0px; margin-top:10px;">Santa Barbara Vecchio Station</h3></td>
				</tr>
				<tr>
					<td><span class="large">Eye of The Day<br/><?php echo "<a href=http://eyeofthedaygdc.com>www.eyeofthedaygdc.com</a>" ?><br /></span></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Brent Freitas</strong><br/>4620 Carpinteria Avenue<br />Carpinteria, CA 93013</br />
					    Show Room: 805/566-6500<br />Cell: 805/895-3007<br />
					    <?php echo mailto('eyeoftheday@vecchiotrees.com'); ?><br />
					</td>
				</tr>

				<tr>
					<td colspan="2"><h3 style="text-align:left; padding-left:0px; margin-top:10px;">Operations</h3></td>
				</tr>

				<tr>
					<td colspan="2"><strong>Vecchio Ranch</strong><br />
								38000 RD 144 <br />
								Visalia, CA 93292 <br />
								559/528-9926
						<p>
<?php	echo '"<iframe width="600" height="450" frameborder="0" style="border:0"
src="https://www.google.com/maps/embed/v1/place?q=38000+Rd+144,+Visalia,+CA+93292,+United+States&key=AIzaSyAsY7FPMu4zEmJejff7odRwvzEtEyTW9js"></iframe>"' ?>
						</p>
					</td>
				</tr>

							 <tr> 
			               	<td colspan="2"><h3 style="text-align:left; padding-left:0px; margin-

top:10px;">General Manager</h3></td> 

			               </tr>
								<tr> 
									<td colspan="2"><strong>Paul McCauley</strong><br/><em>General Manager - 
										<a class="sales_bio" href="#paul">View Bio &raquo;</a>
									</em><br/>949/246-2100<br/><?php echo mailto('paul@vecchiotrees.com'); ?>
										<div style="display:none;">
										<div style="width:600px;" id="paul" 

><br />
<strong>Paul McCauley</strong><br/><em>General Manager</em><br/>949/246-2100<br/><?php echo mailto

('paul@vecchiotrees.com'); ?><br />											
										<p style="padding-left:0px"> 
											Paul McCauley, General Manager at Vecchio Tree’s has been highly involved in the Landscape Design Industry for over 20 years. 

Growing up in the Central Valley, known for the rich agriculture and lush valleys of untouched land, Paul gained his 

love for the great outdoors at an early age with his involvement in the Boy Scouts of America. Through his dedication 

he received the Eagle Scout Award, the highest honor one can receive in the Scouting program.</p>  

											<p style="padding-left:0px"> He 

started his training in the early 80's as he worked and managed the East Coast for Febco Backflow, where he was 

involved with several large Landscape distributors. He would later make his way back to the Golden State of California, 

where he developed and help curate one of the states premier landscape deign firms. Paul quickly became recognized for 

his “Old World” style landscaping. His specialized knowledge of working with large specimen tree’s set him apart, and 

made him an instant leader in the tree market. </p> 

										<p style="padding-left:0px"> This led 

Paul into the wholesale nursery business. He was able to design many large housing developments, private estates and 

high-powered commercial properties all throughout the state. With his training he was able to facilitate the design, 

assisted in projects from the ground up, starting with his team of trained professionals, laying the foundation that 

would be needed to create the final product with pristine beauty of perfection.</p> <br />
										</div><br />
										</div><!-- end hide div -->
										</td> 
								</tr>
								 
			           </table>
		</div>
	</div>
</div>