<?php  
	// initialise controller class
	$class = new Cal;

	// get default display
	$months = $class->index();
?>

<main>
	<div class="card" style="padding-left: 7px; padding-right: 7px">
		<nav style="background-color: transparent;">
		    <div class="nav-wrapper">
		      	<form>
			        <div class="input-field col s12 m11">
			          	<input id="search" type="search" required placeholder="Search by Name, Extension or Location" class="grey darken-1 dynamic-search white-text" data-query="search" data-dest="<?php echo __url__.'/actions/directory.actions.php'; ?>" data-output="tbody">
			          	<label class="label-icon" for="search"><i class="material-icons">search</i></label>
			          	<i class="material-icons clear">close</i>
			        </div>
		      	</form>
		    </div>
		</nav>

		<div class="card">
			<div class="card-content">
				<table class="responsive-table">
					<thead>
						<tr class="blue-text text-darken-3">
							<th>Name</th>
							<th>Description</th>
							<th>Location</th>
							<th>Number</th>
							<th>Extension</th>
						</tr>
					</thead>
					<tbody>
						<?php include("./directory.php") ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</main>	
