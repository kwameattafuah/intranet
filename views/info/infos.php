<?php  
	// initialise controller class
	$class = new Docs;

	// get default
	list($newx,$infos) = $class->index();
?>

<main>
	<div class="row" style="padding-top: 10px">
	    <div class="col s12">
	      <ul class="tabs" style="overflow-x: hidden;">
	      	<li class="tab col s6"><a href="#news" class="active yellow-text text-darken-3">News Items</a></li>
	        <li class="tab col s6"><a href="#info" class="yellow-text text-darken-3">Information</a></li>
	      </ul>
	    </div>
	    <div id="info">
			<div class="row">
				<div class="col s12">
					<nav>
					    <div class="nav-wrapper">
					      	<form>
						        <div class="input-field col s12" style="padding: 0">
						          	<input id="infosearch" type="search" required placeholder="Search by Information or Person who modified it" class="grey darken-2 dynamic-search white-text" data-query="1" data-dest="<?php echo __url__.'/actions/infos.actions.php'; ?>" data-output="tbody">
						          	<label class="label-icon" for="search"><i class="material-icons">search</i></label>
						          	<i class="material-icons">close</i>
						        </div>
					      	</form>
					    </div>
					</nav>
					<div class="card">
						<div class="card-content">
							<table class="responsive-table">
								<thead>
									<tr class="blue-text text-darken-1">
										<th>Article</th>
										<th>Date Modified</th>
										<th>Modified By</th>
									</tr>
								</thead>
								<tbody>
									<?php include("./infodoc.php") ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	    </div>
	    <div id="news">
			<div class="row">
				<div class="col s12">
					<nav>
					    <div class="nav-wrapper">
					      	<form>
						        <div class="input-field col s12" style="padding: 0"">
						          	<input id="newssearch" type="search" required placeholder="Search by News item or Content in it" class="grey darken-2 dynamic-search white-text" data-query="0" data-dest="<?php echo __url__.'/actions/infos.actions.php'; ?>" data-output="tbody">
						          	<label class="label-icon" for="search"><i class="material-icons">search</i></label>
						          	<i class="material-icons">close</i>
						        </div>
					      	</form>
					    </div>
					</nav>
					<div class="card">
						<div class="card-content">
							<table class="responsive-table">
								<thead>
									<tr class="blue-text text-darken-1">
										<th>News Item</th>
										<th>Date Modified</th>
									</tr>
								</thead>
								<tbody>
									<?php include("./news.php") ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	    </div>	    
	</div>
</main>