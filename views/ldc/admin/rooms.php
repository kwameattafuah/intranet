<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldcbook.controller.php");

  // initialise controller class
  $class = new Ldc;

  $rooms = $class->rooms(null);   
?>  
<main>
  <div class="row">
  <table class="responsive-table">
    <thead class="yellow lighten-2">
      <tr class="cursor">
        <th class="center">No.</th>
        <th>Name</th>
        <th>Location</th>
        <th>Occupancy</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody style="overflow-y: auto">
      <?php if ($rooms !== false)
        $no = 0; foreach($rooms as $room) {?>
        <tr class="room">
          <td class="center"><?= ++$no.'.' ?></td>
          <td><?= $room['room_name'] ?></td>
          <td><?= $room['location'] ?></td>
          <td><?= $room['occupancy'] ?></td>
          <td class="action">
            <span class="waves-effect spec-ajax" data-extend-view=".edit" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
            <span class="waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>" data-output=".modal-content" id="<?= $room['id'] ?>" data-query="roomdelete" data-fadeOut=".room"><i class="material-icons">delete</i></span>
          <div class="edit hide">
            <div class="row">
              <div class="col s12">
                <p class="flow-text text-center bold blue-text text-darken-4">Room Information</p>
                <form data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
                  <div class="input-field">
                    <input type="text" id="title" name="title" required class="validate center" value="<?php echo ucwords($room['room_name']) ?>">
                    <label for="title">Name</label>
                  </div>
                  <div class="input-field">
                    <input type="text" id="venue" name="venue" required class="validate center" value="<?php echo ucfirst($room['location']) ?>">
                    <label for="venue">Location</label>
                  </div> 
                  <div class="input-field">
                    <label for="start_date">Maximum Occupancy</label>
                    <input type="number" id="occupancy" name="occupancy" required class="validate center" value="<?php echo $room['occupancy'] ?>">
                  </div>                                                     
                  <div class="input-field center-align">
                    <input type="hidden" name="editroom" value="<?= $room['id'] ?>">
                    <input type="submit" value="UPDATE" class="blue darken-2 btn large">
                  </div>
                </form>
              </div>
            </div>
          </div>          
          </td>
        </tr>          
      <?php } ?>  
    </tbody>
  </table>
    <div class="fixed-action-btn horizontal">
      <a class="btn-floating btn-large green darken-3 waves-effect spec-ajax" data-extend-view=".add" data-parent=".horizontal" data-output=".modal-content" data-toggle="modal" return="true">
        <i class="material-icons">add</i>
      </a>
      <div class="add hide">
        <div class="row">
          <div class="col s12">
            <p class="flow-text text-center bold blue-text text-darken-4">New Room Information</p>
            <form data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
              <div class="input-field">
                <input type="text" id="title" name="title" required class="validate center" value="">
                <label for="title">Room Name</label>
              </div>
              <div class="input-field">
                <input type="text" id="venue" name="venue" required class="validate center" value="">
                <label for="venue">Location</label>
              </div>
              <div class="input-field">
                <label for="start_date">Maximum Occupancy</label>
                <input type="number" id="occupancy" name="occupancy" required class="validate center">
              </div>                                                 
              <div class="input-field center-align">
                <input type="hidden" name="addroom" value="set">
                <input type="submit" value="ADD ROOM" class="blue darken-2 btn large">
              </div>
            </form>
          </div>
        </div>
      </div>       
    </div>  
  </div>
</main>