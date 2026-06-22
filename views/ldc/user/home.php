<?php
// include definition
include("../../../layout/definition.php");

include("../../../controllers/ldc.controller.php");
	
 // initialise controller class
	$class = new Ldc; 

	$histories = $class->history($_SESSION['aj.ldc']['ldcid']);
	$courses = $class->fetch_activecourses($_SESSION['aj.ldc']['ldcid']);

?> 
   
	<div class="card-title">
		<b>Training History</b>
		<a href="#" class="right waves-effect spec-ajax black-text" data-extend-view=".course" data-parent=".card-title" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left red-text">playlist_add</i><span class="red-text hide-on-small-and-down">Add Course</span></a>
        <div class="course card hide">
			<div class="card-title flow-text">COURSE SELECTION</div>
			<div class="card-content">
				<form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" form-type="form" data-output=".modal-content">
					<div class="row">
						<div class="input-field col s8 m10">
							<select class="browser-default" name="course" required>
						      <option value="" disabled selected>Pick a Course please</option>
							    <?php 
									if ($courses !== false)
										foreach ($courses as $course) {
								?> 
						      <option value="<?= $course['id']?>"><?= $course['title'] ?></option>
						      <?php } ?>
						    </select>
						</div>
						<div class="input-field col s4 m2">
							<input type="hidden" name="learn" value="aejay">
							<button type="submit" class="btn blue darken-2">SUBMIT</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>	
	<div class="card-content">	
		<table class="responsive-table highlight">
			<thead>
				<tr class="green-text text-darken-2">
					<th>No.</th>
					<th>Name of Course</th>
					<th class="center">Duration of Course</th>
				</tr>
			</thead>
			<tbody>
		<?php 
		if ($histories !== false){ $j = 0;
			foreach ($histories as $history) { ++$j; ?>
				<tr <?= ($history['active']!=1)? 'grey-text':'' ?>">
					<td><?= $j.'.' ?></td>
					<td><?= $history['title'] ?></td>
					<td class="center"><?= date("M jS, Y", strtotime($history['start_date'])).'  -  '.date("M jS, Y", strtotime($history['end_date']))  ?></td>
				<tr>	
		<?php } } ?>			
			</tbody>
		</table>
	</div>	

<?php 	
		// include foot
	include("../../../layout/foot.php");
?>	
	