<?php  
	// initialise controller class
	$class = new Help;

	// get default
	$requests = $class->requestfetch();
?>

<main>
	<div style="padding: 1% 5% 0% 5%">
		<div class="card">
			<div class="card-content">	
				<table class="responsive-table">		
					<thead>
						<tr class="blue-text text-darken-3">
							<th>Name</th>
							<th>Help Subject</th>
							<th>Contact</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody style="overflow-y: auto;">
						<?php include("./help.php") ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	