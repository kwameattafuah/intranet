<?php  
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

	// initialise controller class
	$class = new Ldc;

	// get default
	$courses = $class->activecourses(9);
?>
<main>
  <div class="row aejay">
      <div class="input-field col s12">
        <h4>Course Materials</h4>
      </div>  
      <div class="input-field col s12 m10">
        <select class="browser-default dynamic-search" id="coursematerial" name="coursematerial" data-output="tbody" data-query="coursematerial" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>">
			<option value="0" disabled selected>Please select a course</option>	
			<?php foreach ($courses as $course) { ?>
				<option value="<?= $course['id'] ?>"><?= $course['title'] ?></option>
			<?php } ?>
		</select>
      </div> 
      <div class="input-field col s2 m2 center action">
        <button type="button" class="btn green darken-3 spec-ajax" style="width: 70%" data-extend-view=".material_space" data-toggle="modal" data-output=".modal-content" data-parent=".action" return="true"><i class="material-icons">library_add</i></button>
        
        <div class="material_space hide">
			<div class="col s12">
				<p class="flow-text text-center bold">New Course Material</p>
				<form data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
			        <div class="input-field">
					    <select class="browser-default" name="course" required>
					      <option value="" disabled selected>Please choose the course</option>
					    <?php 
							if ($courses !== false)
								foreach ($courses as $course) {
						?> 
					      <option value="<?= $course['id']?>"><?= $course['title'] ?></option>
					      <?php } ?>
					    </select>
			        </div>
		            <div class="input-field file-field">
						<div class="btn green">
							<span><i class="material-icons">open_in_browser</i></span>
							<input type="file" name="document[]" multiple="true">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
		            </div>				        				    				
					<div class="input-field center-align">
						<input type="hidden" name="docadd" value="<?= $_SESSION['aj.ldc']['ldcid'] ?>">
						<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
					</div>
				</form>
			</div>        	
        </div>
      </div>
  </div>  
  <div class="row" id="aejay">
    <table class="responsive-table">
      <thead class="yellow lighten-4">
        <tr class="cursor">
          <th>No.</th>
          <th>Name</th>
          <th>Date Added</th>
          <th class="right" style="padding-right: 3%">Action</th>
        </tr>
      </thead>
      <tbody style="overflow-y: auto">
		<tr>
			<td colspan="4" class="center grey-text">Please select a course for its Materials!</td>
		<tr>   
      </tbody>
    </table>
  </div>
</main>