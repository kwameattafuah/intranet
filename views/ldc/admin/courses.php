<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

  // initialise controller class
  $class = new Ldc;

  $courses = $class->courses(null); 
  $rooms = $class->rooms(null);  
?>  
<main>
  <div class="row">
  <table class="responsive-table striped">
    <thead class="yellow lighten-2">
      <tr class="cursor">
        <th class="center">No.</th>
        <th>Title</th>
        <th>Venue</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Class Size</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody style="overflow-y: auto">
      <?php if ($courses !== false) { 
      $no = '0'; 
      foreach($courses as $course) { ?>
        <tr class="course <?= ($course['active'] < 1)? 'grey-text' : '' ?>">
          <td class="center"><?= ++$no.'.' ?></td>
          <td><?= $course['title'] ?></td>
          <td><?= $course['room_name'] ?></td>
          <td><?= $course['start_date'] ?></td>
          <td><?= $course['end_date'] ?></td>
          <td><?= $course['registered'].' of '.$course['capacity'] ?></td>
          <td class="action">
            <span class="waves-effect spec-ajax" data-extend-view=".edit" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
            <span class="waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" id="<?= $course['id'] ?>" data-query="coursedelete" data-fadeOut=".course"><i class="material-icons">delete</i></span>
          <div class="edit hide">
            <div class="row">
              <div class="col s12">
                <p class="flow-text text-center bold blue-text text-darken-4">Course Information</p>
                <form data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
                  <div class="input-field">
                    <input type="text" id="title" name="title" required class="validate center" value="<?php echo $course['title'] ?>">
                    <label for="title">Title</label>
                  </div>
                  <div class="input-field">
                    <input type="text" id="instructor" name="instructor" class="validate center" value="<?php echo $course['instructor'] ?>">
                    <label for="instructor">Instructor</label>
                  </div>
                  <select class="browser-default" name="venue" required>
                  <?php 
                  if ($rooms !== false)
                    foreach ($rooms as $room) {
                ?> 
                    <option value="<?= $room['id']?>" <?= ($room['id'] == $course['venue'])? 'selected' : '' ?> ><?= $room['room_name'] ?> </option>
                    <?php } ?>
                    <option value="0">Not Decided</option>
                  </select>
                  <div class="row">
                    <div class="input-field col s12 m6">
                      <span for="start_date">Start Date</span>
                      <input type="date" id="start_date" name="start_date" required class="validate center" value="<?php echo $course['start_date'] ?>">
                    </div>
                    <div class="input-field col s12 m6">
                      <span for="end_date">End Date</span>
                      <input type="date" id="end_date" name="end_date" required class="validate center" value="<?php echo $course['end_date'] ?>">
                    </div> 
                  </div>
                  <div class="row">
                    <div class="input-field col s12 m6">
                      <span for="reg_start">Registration Start</span>
                      <input type="date" id="reg_start" name="reg_start" required class="validate center" value="<?php echo $course['reg_start'] ?>">
                    </div>
                    <div class="input-field col s12 m6">
                      <span for="reg_end">Registration End Date</span>
                      <input type="date" id="reg_end" name="reg_end" required class="validate center" value="<?php echo $course['reg_end'] ?>">
                    </div> 
                  </div>                    
                  <div class="row">
                    <div class="input-field col s12 m6">
                      <label for="start_date">Class Size</label>
                      <input type="number" id="occupancy" name="occupancy" required class="validate center" value="<?php echo $course['capacity'] ?>">
                    </div>
                    <div class="switch col s12 m6">
                      <h6 class="grey-text">Inactive / Active</h6>
                      <label>
                        <span class="red-text">INACTIVE</span>
                        <input type="checkbox" name="status" <?= ($course['active']==1)? 'checked' : ''; ?> >
                        <span class="lever"></span>
                        <span class="green-text">ACTIVE</span>
                      </label>
                    </div> 
                  </div>                                                    
                  <div class="input-field center-align">
                    <input type="hidden" name="editcourse" value="<?= $course['id'] ?>">
                    <input type="submit" value="UPDATE" class="blue darken-2 btn large">
                  </div>
                </form>
              </div>
            </div>
          </div>          
          </td>
        </tr>          
      <?php } } ?>  
    </tbody>
  </table>
    <div class="fixed-action-btn horizontal">
      <a class="btn-floating btn-large green darken-3 waves-effect spec-ajax" data-extend-view=".add" data-parent=".horizontal" data-output=".modal-content" data-toggle="modal" return="true">
        <i class="material-icons">add</i>
      </a>
      <div class="add hide">
        <div class="row">
          <div class="col s12">
            <p class="flow-text text-center bold blue-text text-darken-4">New Course Information</p>
            <form data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
              <div class="input-field">
                <input type="text" id="title" name="title" required class="validate center" value="">
                <label for="title">Title</label>
              </div>
              <div class="input-field">
                <input type="text" id="instructor" name="instructor" class="validate center" value="">
                <label for="instructor">Instructor</label>
              </div>
              <div class="input-field">
              <select class="browser-default" name="venue" required>
                <option value="" disabled selected>Please choose a venue</option>
              <?php 
              if ($rooms !== false)
                foreach ($rooms as $room) {
              ?> 
                <option value="<?= $room['id']?>"><?= $room['room_name'] ?></option>
                <?php } ?>
                <option>Not Decided</option>
              </select>
              </div>
              <div class="row">
                <div class="input-field col s12 m6">
                  <span for="start_date">Start Date</span>
                  <input type="date" id="start_date" name="start_date" required class="validate center" value="">
                </div>
                <div class="input-field col s12 m6">
                  <span for="end_date">End Date</span>
                  <input type="date" id="end_date" name="end_date" required class="validate center" value="">
                </div> 
              </div>
              <div class="row">
                <div class="input-field col s12 m6">
                  <span for="reg_start">Registration Start</span>
                  <input type="date" id="reg_start" name="reg_start" required class="validate center" value="">
                </div>
                <div class="input-field col s12 m6">
                  <span for="reg_end">Registration End Date</span>
                  <input type="date" id="reg_end" name="reg_end" required class="validate center" value="">
                </div> 
              </div>              
              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="start_date">Class Size</label>
                  <input type="number" id="occupancy" name="occupancy" required class="validate center">
                </div>
                <div class="switch col s12 m6">
                  <h6 class="grey-text">Inactive / Active</h6>
                  <label>
                    <span class="red-text">INACTIVE</span>
                    <input type="checkbox" name="status">
                    <span class="lever"></span>
                    <span class="green-text">ACTIVE</span>
                  </label>
                </div> 
              </div>                                                  
              <div class="input-field center-align">
                <input type="hidden" name="addcourse" value="set">
                <input type="submit" value="CREATE" class="blue darken-2 btn large">
              </div>
            </form>
          </div>
        </div>
      </div>       
    </div>  
  </div>
</main>