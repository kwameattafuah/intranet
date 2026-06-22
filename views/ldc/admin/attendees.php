<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

  // initialise controller class
  $class = new Ldc;

  $courses = $class->courses(null);
  $peeps = $class->attendees(null);   
?>  

<main>
  <div class="row aejay">
    <form class="form" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" form-type="dynamic" return="true">
          <div class="input-field col s12 m8">
            <select class="browser-default dynamic-search" id="course" name="course" data-output="tbody" data-query="course" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>">
              <option value="" disabled selected>Please choose the Course</option>
              <option value="0">All Courses</option>
              <?php 
              if ($courses !== false)
                foreach ($courses as $course) {
              ?> 
              <option value="<?= $course['id']?>"><?= $course['title'] ?></option>
              <?php } ?>
            </select>
          </div>  
          <div class="input-field col s10 m3">
            <select class="browser-default dynamic-dual" data-output="tbody" data-query="course-status" required data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" name="course-status" dual-data="#course">
              <option value="9">All</option>
              <option value="1">Approved</option>
              <option value="-1">Denied</option>
              <option value="0">Pending</option>
            </select>
          </div> 
          <div class="input-field col s2 m1 center">
            <button type="button" class="btn print"><i class="material-icons">print</i></button>
          </div>
    </form>
  </div>  
  <div class="row" id="aejay">
    <table class="responsive-table">
      <thead class="yellow lighten-4">
        <tr class="cursor">
          <th class="center">No.</th>
          <th>Name</th>
          <th>Department</th>
          <th>Course</th>
          <th class="center">Authorisation</th>
        </tr>
      </thead>
      <tbody style="overflow-y: auto">
        <?php include("./attendeex.php") ?> 
      </tbody>
    </table>
  </div>
</main>
