<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

  // initialise controller class
  $class = new Ldc;

  date_default_timezone_set("Africa/Accra");
  $dated = date("Y-m-d");

  $courses = $class->courses($dated);  
  $regs = $class->attended(null); 
  $titles = $class->titles($dated);
?>  
<main>
  <div class="row col s12">
    <div class="card"> 
      <div class="card-title">
        <div class="row" style="margin-bottom: 0px">
          <div class="col s12 m4">
            <h5 class="blue-text text-darken-4">Attendance Records</h5>
          </div>  
            <div class="col s12 m7 right">
              <select class="browser-default dynamic-search" name="course" data-output="tbody" data-query="attendance" required data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>">
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
      <div class="card-content">
        <table class="responsive-table striped">
          <thead>
            <tr class="cursor">
              <th class="center">No.</th>
              <th>Name</th>
              <th>Department</th>
              <th>Status</th>
              <th class="center">Entry Timing</th>
            </tr>
          </thead>
          <tbody style="overflow-y: auto">
            <?php include("./attendancee.php") ?>   
          </tbody>
        </table>
       </div> 
    </div>
      <div class="fixed-action-btn horizontal">
        <a class="btn-floating btn-large red darken-2 waves-effect spec-ajax" data-extend-view=".registrar" data-parent=".horizontal" data-output=".modal-content" data-toggle="modal" return="true">
          <i class="material-icons">add</i>
        </a>
        <div class="hide registrar">
            <form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" form-type="form">
              <h5>Attendance Register</h5>
                <p class="green-text text-darken-5">Course Selection</p>
                <div class="input-field">  
                <select class="browser-default" name="course" required>
                  <option value="" disabled selected>select the course</option>
                <?php 
                if ($titles !== false)
                  foreach ($titles as $title) {
              ?> 
                  <option value="<?= $title['id']?>"><?= $title['title'] ?></option>
                  <?php } ?>
                </select>
                </div>          
                <div class="input-field">
                  <input type="password" name="passphrase" id="passphrase" class="center" required="true" placeholder="Please enter your password">
                  <label for="passphrase">Password</label>
                </div>
                <div class="input-field center-align">
                  <input type="hidden" name="reggen" value="set">
                  <input type="submit" class="btn green darken-2" value="GENERATE">
                </div>
            </form>          
        </div>
      </div>
  </div>  
</main>