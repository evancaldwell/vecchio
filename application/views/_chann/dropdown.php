<script type="javascript" >
$(document).ready(function() {
    $("ul.sf-menu").supersubs({
        minWidth: 12,
        // minimum width of sub-menus in em units 
        maxWidth: 30,
        // maximum width of sub-menus in em units 
        extraWidth: 1 // extra width can ensure lines don't sometimes turn over 
        // due to slight rounding differences and font-family 
    }).superfish(); // call supersubs first, then superfish, so that subs are 
    // not display:none when measuring. Call before initialising 
    // containing tabs for same reason.
        }
</script>

<div id="navigation"> 
    <ul class="sf-menu"> 
        <li> 
            <a href="<?php echo base_url();?>index.php/dir/our_trees/" class="ourtrees">OUR AVAILABILITY</a>    
        </li> 
        <li> 
            <a href="<?php echo base_url();?>index.php/dir/specs_info/intro" class="specsinfo" >SPECS/INFO</a> 
        </li>
        <li> 
            <a href="<?php echo base_url();?>index.php/dir/vecchio_sales" class="contactus">CONTACT US</a> 
        </li>
        <li> 
            <a href="<?php echo base_url();?>index.php/dir/philosophy" class="ourphil">OUR PHILOSOPHY</a> 
        </li>
         <li> 
            <a href="<?php echo base_url();?>index.php/dir/press" class="press">PRESS</a> 
        </li>
    </ul>
</div>