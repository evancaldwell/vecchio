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
            <a href="#" class="ourtrees">OUR TREES</a>    
            <ul>
                <li><a href="#" class="otolives" >OLIVES</a></li>
                <li><a href="#" class="otcitrus" >CITRUS</a></li>
                <li><a href="#" class="otalmonds">ALMONDS</a></li>
                <li><a href="#" class="otpomegranits" >POMEGRANITES</a></li>
                <li><a href="#" class="otfigs" >FIGS</a></li>
                <li><a href="#" class="otitalian">ITALIAN CYPRESS</a></li>
                <li><a href="#" class="otcorkscrew">CORKSCREW WILLOW</a></li>
            </ul>
        </li> 
        <li> 
            <a href="#" class="specsinfo" >SPECS/INFO</a> 
            <ul> 
                <li><a href="#" class="spolives" >OLIVES</a></li>
                <li><a href="#" class="spcitrus" >CITRUS</a></li>
                <li><a href="#" class="spalmonds">ALMONDS</a></li>
                <li><a href="#" class="sppomegranits" >POMEGRANITES</a></li>
                <li><a href="#" class="spfigs" >FIGS</a></li>
                <li><a href="#" class="spitalian">ITALIAN CYPRESS</a></li>
                <li><a href="#" class="spcorkscrew">CORKSCREW WILLOW</a></li>   
            </ul> 
        </li>
        <li> 
            <a href="#" class="contactus">CONTACT US</a> 
            <ul> 
                <li><a href="#" class="ctsales" >SALES</a></li>  
            </ul> 
        </li>
        <li> 
            <a href="#" class="ourphil">OUR PHILOSOPHY</a> 
        </li>
         <li> 
            <a href="#" class="press">PRESS</a> 
        </li>
    </ul>
</div>