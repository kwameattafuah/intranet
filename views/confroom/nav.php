<?php

  // initialise controller class
  $class = new Conf;

  list($books,$rtimes,$ctimes,$state,$priorities,$depts) = $class->index();   
?> 
<header>
   <nav class="container-fluid">
    <div class="nav-wrapper green darken-5 cursor">
      <ul>
        <li class="center flow-text" style="padding-left: 5px"><i class="material-icons left">library_books</i> <span class="bold hide-on-small-and-down">CONFERENCE ROOM PLATFORM</span>
        </li>
        <li class="right cursor"><a href="" class="flow-text waves-effect dropdown-button white-text"><i class="material-icons right spec-ajax" data-query="logout" data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content">exit_to_app</i><?= $_SESSION['aj.gaclintra']['user'] ?></a>        
        </li>           
      </ul>
    </div>
  </nav> 
</header>

<div class="row" style="padding-top: 20px">
  <div class="col s12 m8" id="disp">
    <table class="responsive-table striped">
      <thead class="yellow darken-1">
        <tr class="cursor blue-text text-darken-4">
          <th class="center">No.</th>
          <th>Description</th>
          <th>Booking By</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Start Time</th>
          <th>End Time</th>
        </tr>
      </thead>
      <tbody style="overflow-y: auto">
        <?php if ($books !== false) { 
          $no = 0; foreach($books as $book) {
          $col = ($book['clash'] != 0)? 'red-text' : ''; 
          $spec = (($_SESSION['aj.gaclintra']['role'] === 'SEC' && $_SESSION['aj.gaclintra']['dept'] === 'ICT') || $_SESSION['aj.gaclintra']['role'] === 'ADMIN' || $_SESSION['aj.gaclintra']['role'] === 'PROG' || ($_SESSION['aj.gaclintra']['dept'] === $book['dept']))? 'spec-ajax' : '';  ?>
          <tr class="cursor <?= $col.' '.$spec ?>" data-query="confbooking" data-value="<?= $book['id'] ?>" data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" data-toggle="modal" id="<?= 'booking'.$book['id'] ?>">
            <td class="center"><?= ++$no.'.' ?></td>
            <td><?= $book['description'] ?></td>
            <td><?= $book['booking_by'] ?></td>
            <td><?= date('jS M, Y',strtotime($book['start_date'])) ?></td>
            <td><?= date('jS M, Y',strtotime($book['end_date'])) ?></td>
            <td><?= date('H:i',strtotime($book['start_time'])) ?></td>
            <td><?= date('H:i',strtotime($book['end_time'])) ?></td>
          </tr>       
        <?php } } ?>  
      </tbody>
    </table>
  </div>
  <div class="col s12 m4">

<!-- ROOM STATUS CARD -->
    <div class="card">
      <div class="card-title center green lighten-3"><b>ROOM STATUS</b></div>
      <div class="divider"></div>
      <div class="card-content">
        <?= (!empty($state))? '<p class="center red-text">Currently Unavailable</p>' : '<p class="center green-text text-darken-2">Currently Available</p>'; ?>
      </div>
    </div>
<!--/ ROOM STATUS CARD -->    

<!-- RESTRICTED TIMING CARD -->
    <div class="card">
      <div class="card-title center orange darken-3 white-text">RESTRICTED TIMING</div>
      <div class="divider"></div>
      <div class="card-content">
        <?php if ($rtimes !== false) {?>
          <ul class="collapsible wow fadeInRight" data-wow-delay="0.2s" data-collapsible="accordion">
            <?php foreach ($rtimes as $rtime) { ?>
              <li>
                <div class="collapsible-header center">
                  <span><?= $rtime['description'] ?></span>
                </div>
                <div class="collapsible-body grey lighten-4">
                  <p><b class="blue-text text-darken-2">Booking By: </b> <?= $rtime['booking_by'] ?><br>
                    <b class="blue-text text-darken-2">Start Date/Time: </b> <?= date('jS M. Y', strtotime($rtime['start_date'])).' | '.date('H:i', strtotime($rtime['start_time'])) ?><br>
                    <b class="blue-text text-darken-2">End Date/Time: </b> <?= date('jS M. Y', strtotime($rtime['end_date'])).' | '.date('H:i', strtotime($rtime['end_time'])) ?></p>
                </div>
              </li>
            <?php } ?>  
          </ul>
        <?php }else{ ?>  
          <p class="center grey-text">No Restricted Timing</p>
        <?php } ?>  
      </div>
    </div>
<!--/ RESTRICTED TIMING CARD -->

<!-- TIMING CLASH CARD -->
    <div class="card <?= ( ($_SESSION['aj.gaclintra']['role'] === 'SEC' && $_SESSION['aj.gaclintra']['dept'] !== 'ICT') || $_SESSION['aj.gaclintra']['role'] !== 'ADMIN' )? 'hide' : '' ?>">
      <div class="card-title center red darken-3 white-text">TIMING CLASHES</div>
      <div class="divider"></div>
      <div class="card-content">
        <?php if ($ctimes !== false) {?>
          <ul class="collapsible wow fadeInRight" data-wow-delay="0.2s" data-collapsible="accordion">
            <?php foreach ($ctimes as $ctime) { ?>
              <li>
                <div class="collapsible-header center">
                  <span><?= $ctime['description'] ?></span>
                </div>
                <div class="collapsible-body grey lighten-4">
                  <p><b class="blue-text text-darken-2">Booking By: </b> <?= $ctime['booking_by'] ?><br>
                    <b class="blue-text text-darken-2">Start Date/Time: </b> <?= date('jS M. Y', strtotime($ctime['start_date'])).' | '.date('H:i', strtotime($ctime['start_time'])) ?><br>
                    <b class="blue-text text-darken-2">End Date/Time: </b> <?= date('jS M. Y', strtotime($ctime['end_date'])).' | '.date('H:i', strtotime($ctime['end_time'])) ?></p>
                </div>
              </li>
            <?php } ?>  
          </ul>
        <?php }else{ ?>  
          <p class="center grey-text">No Timing clashes</p>
        <?php } ?>  
      </div>
    </div>    
<!--/ TIMING CLASH CARD -->

  </div>
        
  <div class="action <?= ($_SESSION['aj.gaclintra']['role'] == 'SEC' && $_SESSION['aj.gaclintra']['dept'] !== 'ICT')? '':'hide' ?>">
    <div class="fixed-action-btn horizontal">
      <a href="" class="btn-floating btn-large green waves-effect spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="New Booking" data-extend-view="#book_form" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
            <i class="material-icons">add</i>
          </a>
    </div>

    <div id="book_form" class="hide">
      <form class="form" data-dest="<?= __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" form-type="form">
        <h5 class="blue-text text-darken-4">New Conference Room Reservation</h5>
        <div class="row">
          <div class="input-field">
            <input type="text" name="description" id="description" required="true" value="">
            <label for="description">Booking Description</label>
          </div>
          <div class="input-field">
            <input type="text" name="booking_by" id="booking_by" required="true" value="">
            <label for="booking_by">Booking By</label>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 no-pad-left">
              <span class="grey-text" for="sdate">Start Date</span>
              <input type="date" name="sdate" id="sdate" required="true" value="">  
            </div>
            <div class="input-field col s12 m6 no-pad-right">
              <span class="grey-text" for="stime">Start Time</span>
              <input type="time" name="stime" id="stime" required="true" value="">
            </div>
          </div>  
          <div class="row">
            <div class="input-field col s12 m6 no-pad-left">
              <span class="grey-text" for="edate">End Date</span>
              <input type="date" name="edate" id="edate" required="true" value="">
            </div>
            <div class="input-field col s12 m6 no-pad-right">
              <span class="grey-text" for="etime">End Time</span>              
              <input type="time" name="etime" id="etime" required="true" value="">
            </div>
          </div>          
        </div>    
                                            
        <div class="input-field center-align">
          <input type="hidden" name="addbooking" value="set">
          <button type="submit" class="waves-effect waves-light btn green darken-2" style="width: 50%">Add Booking</button>
        </div>
      </form>           
    </div>
  </div> 

  <div class="action <?= ( ($_SESSION['aj.gaclintra']['role'] === 'SEC' && $_SESSION['aj.gaclintra']['dept'] === 'ICT') || $_SESSION['aj.gaclintra']['role'] === 'ADMIN' || $_SESSION['aj.gaclintra']['role'] === 'PROG')? '' : 'hide' ?>">
      <div class="fixed-action-btn horizontal">
        <a class="btn-floating btn-large green darken-3 waves-effect ">
          <i class="material-icons">menu</i>
        </a>
        <ul>
          <li>
            <a href="" class="btn-floating yellow darken-2 waves-effect waves-light tooltipped" data-position="top" data-delay="50" data-tooltip="Home">
              <i class="material-icons">home</i>
            </a>
          </li> 
          <li>
            <a href="" class="btn-floating green lighten-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Send" data-output=".modal-content" data-toggle="modal" data-dest="<?php echo __url__.'/views/confroom/misc.php' ?>" data-query="notify">
              <i class="material-icons">send</i>
            </a>
          </li>                   
          <li>
            <a href="" class="btn-floating blue lighten-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Booking" data-extend-view="#admin_book_form" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
              <i class="material-icons">add</i>
            </a>
          </li>          
          <li>
            <a href="" class="btn-floating red waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Mail List" data-dest="<?php echo __url__.'/views/confroom/misc.php' ?>" data-output="#disp" data-query="mlist">
              <i class="material-icons">collections</i>
            </a>
          </li>                             
        </ul>
      </div>

    <div id="admin_book_form" class="hide">
      <form class="form" data-dest="<?= __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" form-type="form">
        <h5 class="blue-text text-darken-4">New Conference Room Reservation</h5>
        <div class="row">
          <div class="input-field">
            <input type="text" name="description" id="description" required="true" value="">
            <label for="description">Meeting Description</label>
          </div>
          <div class="input-field">
            <input type="text" name="booking_by" id="booking_by" required="true" value="">
            <label for="booking_by">Booking By</label>
          </div>
          <div class="input-field no-pad">
              <select class="browser-default" name="dept" required>
                <option value="" disabled selected>select Department</option>
              <?php 
              if ($depts !== false)
                foreach ($depts as $dept) {
            ?> 
                <option value="<?= $dept['dept_id']?>"><?= $dept['name'] ?></option>
                <?php } ?>
              </select>
          </div>
          <div class="input-field no-pad">
            <select class="browser-default" name="priority" required>
              <option value="" selected="true" disabled="true">Select Priority</option>
              <?php foreach ($priorities as $priority) { ?>
                <option value="<?= $priority['id'] ?>"><?= $priority['name'] ?></option>
              <?php } ?>  
            </select>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 no-pad-left">
              <span class="grey-text" for="sdate">Start Date</span>
              <input type="date" name="sdate" id="sdate" required="true" value="">  
            </div>
            <div class="input-field col s12 m6 no-pad-right">
              <span class="grey-text" for="stime">Start Time</span>
              <input type="time" name="stime" id="stime" required="true" value="">
            </div>
          </div>  
          <div class="row">
            <div class="input-field col s12 m6 no-pad-left">
              <span class="grey-text" for="edate">End Date</span>
              <input type="date" name="edate" id="edate" required="true" value="">
            </div>
            <div class="input-field col s12 m6 no-pad-right">
              <span class="grey-text" for="etime">End Time</span>              
              <input type="time" name="etime" id="etime" required="true" value="">
            </div>
          </div>          
        </div>    
                                            
        <div class="input-field center-align">
          <input type="hidden" name="addprioritybooking" value="set">
          <button type="submit" class="waves-effect waves-light btn green darken-2" style="width: 50%">Add Booking</button>
        </div>
      </form>           
    </div>

  </div>    

</div>
