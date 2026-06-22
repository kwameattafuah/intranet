<?php
	// include definition
	include("../../../layout/definition.php");

	// include head
	include("../../../layout/head.php");

    if (isset($_GET['ldcquest'])&&$_GET['ldcquest']==='lta') {
    	// include controller
		include("../../../controllers/ldc.controller.php");

		// include nav
		include("./ltanav.php");

		// include foot
		include("../../../layout/foot.php");

	} elseif (isset($_GET['ldcquest'])&&$_GET['ldcquest']==='lbr') {
    	// include controller
		include("../../../controllers/ldc.controller.php");

		// include nav
		include("./lbrnav.php");

		// include foot
		include("../../../layout/foot.php");

	} else {
?>	
	<div class="container">
		<div class="center">
			<div class="card darken-1">
				<div class="card-title" style="padding-top: 1%">
					<h4>ADMIN MODULE SELECTION</h4>
				</div>
				<div class="divider"></div>
                <div class="card-content white-text">
                	<div class="row">
	                	<div class="col s12 m5 card blue left cursor">
		                  <a href="<?= __url__.'/ldc/admin/?ldcquest=lta' ?>" class="white-text waves-effect">
		                  	<i class="material-icons medium">explore</i>
		                  <span>LDC Training</span></a>
		                </div>
		                <div class="col s12 m5 card green right cursor">
		                  <a href="<?= __url__.'/ldc/admin/?ldcquest=lbr' ?>" class="white-text waves-effect">
		                  	<i class="material-icons medium">assignment</i>
		                  <span>LDC Rooms</span></a>
		                </div>  
		            </div>    
                </div>
            </div>    
        </div>
    </div>    
<?php } ?>
