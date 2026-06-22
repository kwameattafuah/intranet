<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

  // initialise controller class
  $class = new Ldc;

  list($peeps,$upeeps,$courses,$recents) = $class->index();   
?>  
<main>
  <div class="row">

    <div class="col s12 m6">
      <div class="card z-depth-2 red darken-2">
        <div class="card-content" style="padding-bottom: 0px">
          <span class="card-title white-text">Authorised Participants</span>
          <div class="divider"></div>
          <div class="row">
            <p class="flow-text white-text"><?php echo $peeps !== false? count($peeps):0; ?>
            <i class="material-icons right white-text" style="padding-top: 5px">groups</i></p>
          </div>  
        </div>
        <div class="card-action">
          <a href="" class="white-text spec-ajax" data-extend-view=".peep-info" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true">VIEW MORE</a>
          <div class="peep-info hide">
            <div class="row">
              <div class="col s12">
                <p class="center-align flow-text blue-text text-darken-4">Attendee Information</p>
                <?php if ($peeps !== false)
                foreach ($peeps as $peep): ?>
                <ul class="collapsible" data-collapsible="accordion">
                  <li>
                    <div class="collapsible-header">
                    <?php echo ucwords(strtolower($peep['name']))?>
                    </div>
                    <div class="collapsible-body">
                      <div class="card z-depth-2">
                        <div class="card-content row">
                          <div class="col s12 m6">
                            <p><b>Position:</b> <?php echo $peep['position'] ?></p>
                            <p><b>Staff ID:</b> <?php echo $peep['staff_id'] ?></p>
                            <p><b>Phone:</b> <?php echo $peep['phone'] ?></p>
                            <p><b>E-mail:</b> <?php echo $peep['email'] ?></p>
                          </div>
                          <div class="col s12 m6">
                            <p><b>Course:</b> <?php echo ucwords($peep['title']) ?></p>
                            <p><b>Status:</b> <?php echo ($peep['status'] == 1)? '<span class="green-text"> Approved' : '<span class="red-text"> Denied' ?> </span></p>
                            <p class="wrap"><b>Remark(s):</b> <?php echo (!empty($peep['comment'])) ? $peep['comment'] : '<span class="grey-text"> N/A </span>' ?> </p>
                          </div> 
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
                <?php endforeach ?>
              </div>
            </div>
          </div>
        </div>      
      </div>
    </div>

    <div class="col s12 m6">
      <div class="card z-depth-2 brown darken-2">
        <div class="card-content" style="padding-bottom: 0px">
          <span class="card-title white-text">Pending Authorisations</span>
          <div class="divider"></div>
          <div class="row">
            <p class="flow-text white-text"><?php echo $upeeps !== false? count($upeeps):0; ?>
            <i class="material-icons right white-text" style="padding-top: 5px">error</i></p>
          </div>  
        </div>
        <div class="card-action">
          <a href="" class="white-text spec-ajax" data-extend-view=".upeep-info" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true">VIEW MORE</a>
          <div class="upeep-info hide">
            <div class="row">
              <div class="col s12">
                <p class="center-align flow-text blue-text text-darken-4">Pending Attendee Information</p>
                <?php if ($upeeps !== false)
                foreach ($upeeps as $upeep): ?>
                <ul class="collapsible" data-collapsible="accordion">
                  <li>
                    <div class="collapsible-header">
                    <?php echo ucwords(strtolower($upeep['name'])) ?>
                    </div>
                    <div class="collapsible-body">
                      <div class="card z-depth-2">
                        <div class="card-content row">
                          <div class="col s12 m6">
                            <p><b>Position:</b> <?php echo $upeep['position'] ?></p>
                            <p><b>Staff ID:</b> <?php echo $upeep['staff_id'] ?></p>
                            <p><b>Phone:</b> <?php echo $upeep['phone'] ?></p>
                            <p><b>E-mail:</b> <?php echo $upeep['email'] ?></p>
                          </div>
                          <div class="col s12 m6">
                            <p><b>Course:</b> <?php echo ucwords($upeep['title']) ?></p>
                            <p><b>Date Applied:</b> <?php echo date('jS M, Y', strtotime($upeep['date_reg'])) ?></p>
                            <p><b>Status:</b> <?php echo '<span class="red-text"> Pending' ?> </span></p>
                          </div>  
                        </div>
                        <div class="card-action center">
                          <form class="form" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" form-type="dynamic" >
                            <div class="row">
                              <div class="input-field col s12 m9 no-pad-left">
                                <select class="browser-default" name="answer" required>
                                  <option value="" disabled selected>Choose Authorisation</option>
                                  <option value="1" >Participant Approved</option>
                                  <option value="-1" >Participant Denied</option>
                                </select>
                              </div>
                              <div class="input-field col s12 m3 center-align">
                                  <input type="hidden" name="authorise" value="<?= $upeep['individual_id'] ?>">
                                  <input type="hidden" name="thecourse" value="<?= $upeep['course_id'] ?>">
                                  <button type="submit" id="submit" class="btn green darken-2 white-text" > CONFIRM </button> 
                              </div>            
                            </div> 
                            <div class="input-field">                               
                              <textarea name="message" id="message" placeholder="Type your comment here" class="materialize-textarea"></textarea>
                              <label for="message">Remark(s)</label>
                            </div>  
                          </form>  
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
                <?php endforeach ?>
              </div>
            </div>
          </div>
        </div>       
      </div>
    </div>

  </div>
  
<div class="row">

  <div class="col s12 m6">
    <div class="card z-depth-2 yellow darken-3">
      <div class="card-content" style="padding-bottom: 0px">
        <span class="card-title white-text">The Active Courses</span>
        <div class="divider"></div>
        <div class="row">
          <p class="flow-text white-text"><?php echo $courses !== false? count($courses):0; ?>
          <i class="material-icons right white-text" style="padding-top: 5px">collections_bookmark</i></p>
        </div>
      </div>
      <div class="card-action">
        <a href="" class="white-text spec-ajax" data-extend-view=".peep-info" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true">VIEW MORE</a>
        <div class="peep-info hide">
          <div class="row">
            <div class="col s12">
              <p class="center-align flow-text blue-text text-darken-4">Course Information</p>
              <?php if ($courses !== false)
                  foreach ($courses as $course): ?>
              <ul class="collapsible" data-collapsible="accordion">
                <li>
                  <div class="collapsible-header">
                  <?php echo strtoupper($course['title']) ?>
                  </div>
                  <div class="collapsible-body">
                    <div class="aejay">
                        <p><b class="blue-text text-darken-4">Start Date:</b> <?php echo date("l, jS F, Y", strtotime($course['start_date'])) ?></p>
                        <p><b class="blue-text text-darken-4">End Date:</b> <?php echo date("l, jS F, Y", strtotime($course['end_date'])) ?></p>
                        <p><b class="blue-text text-darken-4">Venue:</b> <?php echo $course['room_name'] ?></p>
                        <p><b class="blue-text text-darken-4">Status:</b> <?php echo $course['registered'].' of '.$course['capacity'] ?></p>
                    </div>
                   </div> 
                </li>
              </ul>
              <?php endforeach ?>
            </div>
          </div>
        </div>
      </div>       
    </div>
  </div>

  <div class="card-content col s12 m6">
    <div class="card row">
        <div class="card-title blue darken-2" style="padding-left: 3%; padding-top: 8px;">Ongoing Courses</div>
        <div class="card-content" style="max-height: 370px; overflow-y: auto">
        <?php 
        if ($recents !== false) { 
          foreach ($recents as $recent) {
        ?>
          <div class="cursor">
            <a href="" class="spec-ajax" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" data-toggle="modal" id="<?= $recent['id'] ?>" data-query="viewcourse">                  
              <p class="green-text text-darken-4"><?php echo ucwords($recent['title']); ?></p>
            </a>
            <div class="divider"></div>
          </div>
          <?php } } ?>
        </div>
      </div>
    </div>

  </div>    
</div> 

</main>  


<script>
  $(document).ready(function(){

    $(document).on("click", "[data-toggle='modal']", function(e){
      $(".collapsible").collapsible({
        accordion: true
      });    
    });
  });
</script>