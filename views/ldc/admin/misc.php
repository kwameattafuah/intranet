<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

  // initialise controller class
  $class = new Ldc;

  $courses = $class->activecourses(9); 

  if (isset($_POST['search']) && $_POST['id'] === 'eval-status' ) {

    $courses = $class->activecourses($_POST['search']); 

    if ($courses!==false){ 
      $no = 0; foreach($courses as $course) { ?>
      <tr>
        <td class="center"><?= ++$no.'.' ?></td>
        <td><?= ucwords(strtolower($course['title'])) ?></td>
        <td><?= date('M jS, Y',(strtotime($course['start_date']))).' - '.date('M jS, Y',(strtotime($course['end_date']))) ?></td>
        <td class="bold"><?= $course['appraisal_key'] ?></td>
        <td>
          <div class="switch">
            <label>
              <span class="red-text">Inactive</span>
              <input type="checkbox" <?= $course['evaluate'] == 1? "checked":"" ?> class="validate dynamic-search" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-query="<?= $course['id'] ?>" data-output=".modal-content" data-id="activatecourse">
              <span class="lever"></span>
              <span class="green-text">Active</span>
            </label>
          </div>
        </td>
      </tr>         
  <?php 
      } 
    } 
  } else{
?>  
<main>
  <div class="row aejay">
    <form class="form">
      <div class="input-field col s12 m8">
        <p class="flow-text">Course Evaluations</p>
      </div>  
      <div class="input-field col s10 m4">
        <select class="browser-default dynamic-search" data-output="tbody" data-query="eval-status" required data-dest="<?php echo __url__.'/views/ldc/admin/misc.php' ?>" name="eval-status">
          <option value="9">All</option>
          <option value="1">Activated</option>
          <option value="0">Pending</option>
        </select>
      </div> 
    </form>
  </div>  
  <div class="row" id="aejay">
    <table class="responsive-table">
      <thead class="yellow lighten-4">
        <tr class="cursor">
          <th class="center">No.</th>
          <th>Name</th>
          <th>Course Duration (span)</th>
          <th>Appraisal Key</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody style="overflow-y: auto">
        <?php
          if ($courses!==false){ 
            $no = 0; foreach($courses as $course) { ?>
            <tr>
              <td class="center"><?= ++$no.'.' ?></td>
              <td><?= ucwords(strtolower($course['title'])) ?></td>
              <td><?= date('M jS, Y',(strtotime($course['start_date']))).' - '.date('M jS, Y',(strtotime($course['end_date']))) ?></td>
              <td class="bold"> <?= $course['appraisal_key'] ?></td>
              <td>
                <div class="switch">
                  <label>
                    <span class="red-text">Inactive</span>
                    <input type="checkbox" <?= $course['evaluate'] == 1? "checked":"" ?> class="validate dynamic-search" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-query="<?= $course['id'] ?>" data-output=".modal-content" data-id="activatecourse">
                    <span class="lever"></span>
                    <span class="green-text">Active</span>
                  </label>
                </div>
              </td>
            </tr>         
        <?php 
            } 
          } 
        ?>   
      </tbody>
    </table>
  </div>
</main>
<?php } ?>