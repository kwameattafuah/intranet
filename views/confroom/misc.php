<?php  
  // include define
  include("../../layout/definition.php");
  // include controller class
  include("../../controllers/conf.controller.php");
  // initialise controller class
  $class = new Conf; 

  if (isset($_POST['query']) && $_POST['query'] == "addmgroup") { ?>
    <div class="col s12">
			<form class="form" data-dest="<?= __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" form-type="form">
        <h5 class="brown-text text-darken-2">New Mail Group</h5>
        <div class="input-field">
          <input type="text" id="name" name="name" required="true">
          <label for="name">Group Name</label>
        </div>
        <div class="input-field center-align">
          <input type="hidden" name="newmailgroup" value="set">
          <input type="submit" value="CREATE" class="btn green darken-2">
        </div>
      </form>			        		
    </div>
<?php } if (isset($_POST['query']) && $_POST['query'] == "notify") {
          $num = $class->fetchbookingsNum(); 
          $num = $num['booking_number'];
          if ($num > 0) {
           $groups = $class->fetchmailgroups();
  ?>
            <div class="col s12">
              <form class="form" data-dest="<?= __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" form-type="form">
                <h5 class="red-text text-darken-2">CONFERENCE ROOM BOOKING NOTIFICATION</h5>
                <div>
                  <p>Confirm Dispatch Notifications for MAIL GROUP:</p>
                </div>
                <div class="input-field col s12 m6">
                  <select class="browser-default dynamic-search" id="mgrp" name="mgrp" data-query="mgrp" data-dest="<?php echo __url__.'/views/confroom/misc.php' ?>">
                    <option value="" disabled>Please choose the Mail Group</option>
                    <option value="0" selected>All Mail Groups</option>
                    <?php 
                    if ($groups !== false)
                      foreach ($groups as $group) {
                    ?> 
                    <option value="<?= $group['id']?>"><?= ucwords(strtolower($group['name'])) ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="input-field center-align">
                  <input type="hidden" name="mailnotify" value="set">
                  <input type="submit" value="SEND" class="btn green darken-2">
                </div>
              </form>                 
            </div>
<?php }else{ ?>
        <p class="red-text flow-text center-align">Please there are <b>NO Bookings</b> for Notification Dispatch!</p>
  <?php  }
  }

  if (isset($_POST['search']) &&  $_POST['id'] == "mgrp") {
    $search = ($_POST['search'] != 0)? $_POST['search'] : null;
    $receivers = $class->fetchmailreceiver($search);
    $groups = $class->fetchmailgroups();

    if ($receivers!==false){ 
      $no = 0; foreach($receivers as $receiver) { ?>
      <tr class="receiver">
        <td class="center"><?= ++$no.'.' ?></td>
        <td><?= ucwords(strtolower($receiver['name'])) ?></td>
        <td><?= strtolower($receiver['email']) ?></td>
        <td><?= ucwords(strtolower($receiver['group_name'])) ?></td>
        <td class="action">
                <span class="waves-effect spec-ajax" data-extend-view=".edit" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
                <span class="waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" id="<?= $receiver['id'] ?>" data-query="receiverdelete" data-fadeOut=".receiver"><i class="material-icons">delete</i></span>
              <div class="edit hide">
                <div class="row">
                  <div class="col s12">
                    <p class="flow-text text-center bold blue-text text-darken-4">Recepient Information</p>
                    <form data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
                      <div class="input-field">
                        <input type="text" id="name" name="name" required class="validate center" value="<?php echo ucwords($receiver['name']) ?>">
                        <label for="name">Name</label>
                      </div>
                      <div class="input-field">
                        <input type="email" id="email" name="email" required class="validate center" value="<?php echo ucfirst($receiver['email']) ?>">
                        <label for="email">Email Address</label>
                      </div>
                          <div class="input-field no-pad">
                    <select class="browser-default" id="group" name="group">
                      <option value="" disabled>Please choose the Mail Group</option>
                      <?php 
                      if ($groups !== false)
                        foreach ($groups as $group) {
                      ?> 
                      <option value="<?= $group['id']?>" <?= ($group['id'] == $receiver['group_id'])? 'selected':'' ?> ><?= strtolower($group['name']) ?></option>
                      <?php } ?>
                    </select>
                </div>             
                      <div class="input-field center-align">
                        <input type="hidden" name="editreceiver" value="<?= $receiver['id'] ?>">
                        <input type="submit" value="UPDATE" class="blue darken-2 btn large">
                      </div>
                    </form>
                  </div>
                </div>
              </div>          
            </td>
      </tr>         
  <?php 
      } 
    }else{ 
      echo '<tr class="red-text"><td colspan="5" class="center">No Mail Receiver(s) List Created!</td></tr>';
    }
  }   

  if (isset($_POST['query']) && $_POST['query'] == "mlist") { 
    $groups = $class->fetchmailgroups();
    $receivers = $class->fetchmailreceiver(null); ?>
    <section>
      <div class="row aejay">
        <form class="form">
              <div class="input-field col s12 m6">
                <select class="browser-default dynamic-search" id="mgrp" name="mgrp" data-output="tbody" data-query="mgrp" data-dest="<?php echo __url__.'/views/confroom/misc.php' ?>">
                  <option value="" disabled>Please choose the Mail Group</option>
                  <option value="0">All Mail Groups</option>
                  <?php 
                  if ($groups !== false)
                    foreach ($groups as $group) {
                  ?> 
                  <option value="<?= $group['id']?>"><?= ucwords(strtolower($group['name'])) ?></option>
                  <?php } ?>
                </select>
              </div>  
              <div class="input-field col s6 m3">
          <a href="#" class="right waves-effect spec-ajax" data-extend-view=".mreceiver" data-parent=".aejay" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left blue-text">playlist_add</i><span class="blue-text hide-on-small-and-down">Add Recepient</span></a>
              </div> 
              <div class="input-field col s6 m3">
                <a href="#" class="right waves-effect spec-ajax" data-extend-view=".mgroup" data-parent=".aejay" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left red-text">playlist_add</i><span class="red-text hide-on-small-and-down">Mail Groups</span></a>
              </div>
        </form>
              <div class="mreceiver hide">
              <div class="row">
                <div class="col s12">
                  <h5 class="blue-text text-darken-4">New Mail Recepient</h5>
                    <form data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
                      <div class="input-field">
                        <input type="text" id="name" name="name" required class="validate" value="">
                        <label for="name">Name</label>
                      </div>
                      <div class="input-field">
                        <input type="email" id="email" name="email" required class="validate" value="">
                        <label for="email">Email</label>
                      </div>
                          <div class="input-field no-pad">
                    <select class="browser-default" id="group" name="group">
                      <option value="" disabled>Please choose Mail Group</option>
                      <?php 
                      if ($groups !== false)
                        foreach ($groups as $group) {
                      ?> 
                      <option value="<?= $group['id']?>"><?= ucwords(strtolower($group['name'])) ?></option>
                      <?php } ?>
                    </select>
                </div>             
                      <div class="input-field center-align">
                        <input type="hidden" name="addreceiver" value="set">
                        <input type="submit" value="ADD RECEPIENT" class="blue darken-2 btn large">
                      </div>
                    </form>
                </div>
              </div>
            </div>

            <div class="mgroup hide">
              <div class="row">
                <div class="col s12">
                  <form data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
                  <p><span class="flow-text"><b>MAIL GROUPS</b></span>
                    <span class="right">
                      <button type="submit" class="waves-effect waves-yellow btn-flat"><i class="left red-text material-icons">delete</i><span class="red-text hide-on-small-and-down">Delete</span></button>
                      <button class="waves-effect spec-ajax waves-green btn-flat" data-dest="<?php echo __url__.'/views/confroom/misc.php' ?>" data-query="addmgroup" data-output=".modal-content"><i class="material-icons left green-text">add</i><span class="green-text hide-on-small-and-down">Create Group</span></button>
                    </span>  
                      
                  </p>
                    <div class="input-field no-pad">
                      <select class="browser-default" id="delgroup" name="delgroup" required="true">
                        <option value="" selected disabled>Please select a Mail Groups</option>
                        <?php 
                        if ($groups !== false)
                          foreach ($groups as $group) {
                        ?> 
                        <option value="<?= $group['id']?>"><?= ucwords(strtolower($group['name'])) ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <input type="hidden" name="deletemgroup" value="delete"> 
                  </form>                 
                </div>
              </div>
              
          </div> 
        </div>    

      <div class="row" id="aejay">
        <table class="responsive-table">
          <thead class="yellow lighten-4">
            <tr class="cursor">
              <th class="center">No.</th>
              <th>Name</th>
              <th>Email</th>
              <th>Group</th>
              <th class="center">Action</th>
            </tr>
          </thead>
          <tbody style="overflow-y: auto">
            <?php
          if ($receivers!==false){ 
            $no = 0; foreach($receivers as $receiver) { ?>
            <tr class="receiver">
              <td class="center"><?= ++$no.'.' ?></td>
              <td><?= ucwords(strtolower($receiver['name'])) ?></td>
              <td><?= strtolower($receiver['email']) ?></td>
              <td><?= ucwords(strtolower($receiver['group_name'])) ?></td>
              <td class="action">
                      <span class="waves-effect spec-ajax" data-extend-view=".edit" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
                      <span class="waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" id="<?= $receiver['id'] ?>" data-query="receiverdelete" data-fadeOut=".receiver"><i class="material-icons">delete</i></span>
                    <div class="edit hide">
                      <div class="row">
                        <div class="col s12">
                          <p class="flow-text text-center bold blue-text text-darken-4">Recepient Information</p>
                          <form data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
                            <div class="input-field">
                              <input type="text" id="name" name="name" required class="validate" value="<?php echo ucwords($receiver['name']) ?>">
                              <label for="name">Name</label>
                            </div>
                            <div class="input-field">
                              <input type="email" id="email" name="email" required class="validate" value="<?php echo ucfirst($receiver['email']) ?>">
                              <label for="email">Email</label>
                            </div>
                                      <div class="input-field no-pad">
                          <select class="browser-default" id="group" name="group">
                            <option value="" disabled>Please choose Mail Group</option>
                            <?php 
                            if ($groups !== false)
                              foreach ($groups as $group) {
                            ?> 
                            <option value="<?= $group['id']?>" <?= ($group['id'] == $receiver['group_id'])? 'selected':'' ?> ><?= ucwords(strtolower($group['name'])) ?></option>
                            <?php } ?>
                          </select>
                        </div>             
                            <div class="input-field center-align">
                              <input type="hidden" name="editreceiver" value="<?= $receiver['id'] ?>">
                              <input type="submit" value="UPDATE" class="blue darken-2 btn large">
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>          
                  </td>
            </tr>         
        <?php 
            } 
          }else{ 
            echo '<tr class="red-text"><td colspan="5" class="center">No Mail Receiver(s) List Created!</td></tr>';
          } 
        ?>  
          </tbody>
        </table>
      </div>

    </section>
  <?php }  ?>