<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

  // initialise controller class
  $class = new Ldc;

  $courses = $class->all_courses();  
?>  
<main>
  <div class="row col s12">
    <div class="card"> 
      <div class="card-title">
        <div class="row" style="margin-bottom: 0px">
          <div class="col s12 m4">
            <h5 class="blue-text text-darken-4">Participant Appraisals</h5>
          </div>  
            <div class="col s12 m7 right">
              <select class="browser-default dynamic-search" name="appraisedparticipants" data-output=".card-content" data-query="appraisedparticipants" required data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>">
                <option value="" disabled selected>Please select a course</option>
                <?php 
                  if ($courses !== false)
                    foreach ($courses as $course) {
                ?> 
                <option value="<?= $course['id'] ?>"><?= $course['title'] ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div> 
    </div>  
    <div class="card-content">
      <h5 class="center-align red-text text-darken-4" style="padding-top: 20%"> Please Select a Course to view the appraised Participants </h5>
    </div> 
  </div>  
</main>