<?php
// include definition
include("../../../layout/definition.php");

include("../../../controllers/ldc.controller.php");
	
 // initialise controller class
 $class = new Ldc; 

  $courses = $class->current_courses($_SESSION['aj.ldc']['ldcid']);  
?> 
   
	<div class="card-title">
		<b class="right red-text">Study Materials</b>
	</div>	
	<div class="card-content">
		<div class="row input-field">
			<select class="browser-default dynamic-search" id="coursematerial" name="coursematerial" data-output="tbody" data-query="coursematerial" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" style="width: 60%">
				<option value="0" disabled selected>select course if available</option>	
				<?php foreach ($courses as $course) { ?>
					<option value="<?= $course['id'] ?>"><?= $course['title'] ?></option>
				<?php } ?>
			</select>
		</div>	
		<table class="responsive-table highlight">
			<thead>
				<tr class="green-text text-darken-2">
					<th>No.</th>
					<th class="text-center">Name of Material</th>
					<th>Date Added</th>
					<th class="right">Action</th>
				</tr>
			</thead>
			<tbody>
				<tr class="wow zoomIn">
					<td colspan="4" class="center grey-text">No Study Material(s) Available! </td>
				<tr>			
			</tbody>
		</table>
	</div>	

<?php 	
		// include foot
	include("../../../layout/foot.php");
?>	
	