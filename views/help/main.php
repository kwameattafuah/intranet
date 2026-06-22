<?php  
	$class = new Help;

	// get default
	$infos = $class->index();
?>

<main>
<div class="container-fluid" style="margin-top: 20px">
	<div class="row">
		<div class="col s12 m7">
			<nav class="container-fluid  wow fadeInLeft">
			    <div class="nav-wrapper">
			      	<form>
				        <div class="input-field">
				          	<input id="search" type="search" required placeholder="Please type your Help Issue" class="grey darken-1 dynamic-search white-text" data-query="search" data-dest="<?php echo __url__.'/actions/help.actions.php'; ?>" data-output=".info-area">
				          	<label class="label-icon" for="search"><i class="material-icons">search</i></label>
				          	<i class="material-icons clear">close</i>
				        </div>
			      	</form>
			    </div>
			</nav>
			<div class="card">
				<div class="card-content info-area" style="height: 500px; overflow-y: auto;">
					<?php include("./info.php") ?>
				</div>
			</div>						
		</div>	
		<div class="col s12 m5" >
	    	<?php include("feed.php") ?>		
	    </div>
	</div>
</div>	
</main>
