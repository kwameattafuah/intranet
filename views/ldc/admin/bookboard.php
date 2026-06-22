<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldcbook.controller.php");

  // initialise controller class
  $class = new Ldc;

  list($books,$ubooks,$feedbacks,$recents) = $class->index();  
?>  
<main>
  <div class="row">
    <div class="col s12 m4 l4">
      <div class="card z-depth-2 red darken-2">
        <div class="card-content" style="padding-bottom: 0px">
          <span class="card-title white-text">Authorised Bookings</span>
          <div class="divider"></div>
          <div class="row">
            <p class="flow-text white-text"><?php echo $books !== false? count($books):0; ?>
            <i class="material-icons right white-text" style="padding-top: 5px">groups</i></p>
          </div>  
        </div>
        <div class="card-action">
          <a href="" class="white-text spec-ajax" data-extend-view=".book-info" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true">VIEW MORE</a>
          <div class="book-info hide">
            <div class="row">
              <div class="col s12">
                <p class="center-align flow-text green-text text-darken-4">Booking Information</p>
                <ul class="collapsible" data-collapsible="accordion">
                <?php if($books !== false) 
                  foreach ($books as $book): ?>
                  <li>
                    <div class="collapsible-header">
                    <?php echo '<span class="green-text text-darken-2">'.ucwords($book['room_name']).'</span> - '.ucfirst($book['booked_by']) ?>
                    </div>
                    <div class="collapsible-body row">
                      <div class="col s12 m6">
                        <p><b class="blue-text text-darken-4">Purpose:</b><br> <?php echo ucwords($book['purpose']) ?></p>
                        <p><b class="blue-text text-darken-4">Start Date/Time:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($book['start_dt'])).' GMT'; ?></p>
                        <p><b class="blue-text text-darken-4">End Date/Time:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($book['end_dt'])).' GMT'; ?></p>
                        <p><b class="blue-text text-darken-4">Status:</b><br> <?php echo ($book['status'] != 0)? '<span class="green-text"> Approved' : '<span class="red-text"> Denied' ?></span></p>
                      </div>  
                      <div class="col s12 m6">
                        <p><b class="blue-text text-darken-4">Department:</b><br> <?php echo ucwords($book['dept']) ?></p>
                        <p><b class="blue-text text-darken-4">Date Booked:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($book['book_date'])).' GMT'; ?></p>
                        <p><b class="blue-text text-darken-4">Approved By:</b><br> <?php echo $book['approval'] ?></p>
                        <p><b class="blue-text text-darken-4">Date Approved:</b><br> <?php echo $book['approval_date'] ?></p>
                      </div> 
                      <div class="col s12">
                        <p class="blue-text text-darken-4 wrap"><b>Comment(s):</b><br> <?php echo $book['comments'] ?></p>
                      </div> 
                    </div>  
                  </li>
                <?php endforeach ?>
                </ul>
              </div>
            </div>
          </div>
        </div>      
      </div>
    </div>

    <div class="col s12 m4 l4">
      <div class="card z-depth-2 brown darken-2">
        <div class="card-content" style="padding-bottom: 0px">
          <span class="card-title white-text">Pending Bookings</span>
          <div class="divider"></div>
          <div class="row">
            <p class="flow-text white-text"><?php echo $ubooks !== false? count($ubooks):0; ?>
            <i class="material-icons right white-text" style="padding-top: 5px">error</i></p>
          </div>  
        </div>
        <div class="card-action">
          <a href="" class="white-text spec-ajax" data-extend-view=".ubook-info" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true">VIEW MORE</a>
          <div class="ubook-info hide">
            <div class="row">
              <div class="col s12">
                <p class="center-align flow-text red-text text-darken-4">Pending Bookings</p>
                <ul class="collapsible" data-collapsible="accordion">
                <?php if($ubooks !== false)
                  foreach ($ubooks as $ubook): ?>
                  <li>
                    <div class="collapsible-header">
                    <?php echo '<span class="yellow-text text-darken-4">'.ucwords($ubook['room_name']).'</span> - '.ucfirst($ubook['booked_by']) ?>
                    </div>
                    <div class="collapsible-body row">
                      <div class="col s12 m6">
                        <p><b class="blue-text text-darken-4">Purpose:</b><br> <?php echo ucwords($ubook['purpose']) ?></p>
                        <p><b class="blue-text text-darken-4">Start Date/Time:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($ubook['start_dt'])).' GMT'; ?></p>
                        <p><b class="blue-text text-darken-4">End Date/Time:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($ubook['end_dt'])).' GMT'; ?></p>
                      </div>  
                      <div class="col s12 m6">
                        <p><b class="blue-text text-darken-4">Department:</b><br> <?php echo ucwords($ubook['dept']) ?></p>
                        <p><b class="blue-text text-darken-4">Date Booked:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($ubook['book_date'])).' GMT'; ?></p>
                        <p><b class="blue-text text-darken-4">Status:</b><br> <?php echo ('<span class="red-text"> Pending'); ?></span></p>
                      </div>  
                    </div> 
                  </li>
                <?php endforeach ?>
                </ul>
              </div>
            </div>
          </div>
        </div>       
      </div>
    </div>

    <div class="col s12 m4 l4">
      <div class="card z-depth-2 yellow darken-3">
        <div class="card-content" style="padding-bottom: 0px">
          <span class="card-title white-text">Currently In - Use</span>
          <div class="divider"></div>
          <div class="row">
            <p class="flow-text white-text"><?php echo $recents !== false? count($recents):0; ?>
            <i class="material-icons right white-text" style="padding-top: 5px">collections_bookmark</i></p>
          </div>
        </div>
        <div class="card-action">
          <a href="" class="white-text spec-ajax" data-extend-view=".book-info" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true">VIEW MORE</a>
          <div class="book-info hide">
            <div class="row">
              <div class="col s12">
                <p class="center-align flow-text blue-text text-darken-4">Rooms Currently In-use</p>
                <?php if ($recents !== false)
                    foreach ($recents as $recent): ?>
                <ul class="collapsible" data-collapsible="accordion">
                  <li>
                    <div class="collapsible-header">
                    <?php echo strtoupper($recent['room_name']) ?>
                    </div>
                    <div class="collapsible-body row">
                      <div class="col s12 m6">
                        <p><b class="green-text text-darken-4 wrap">Purpose:</b><br> <?php echo ucwords($recent['purpose']) ?></p>
                        <p><b class="blue-text text-darken-4">Start Date/Time:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($recent['start_dt'])).' GMT'; ?></p>
                        <p><b class="blue-text text-darken-4">End Date/Time:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($recent['end_dt'])).' GMT'; ?></p>
                        <p><b class="blue-text text-darken-4">Date Booked:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($recent['book_date'])).' GMT'; ?></p>
                      </div>  
                      <div class="col s12 m6">
                        <p><b class="blue-text text-darken-4 wrap">Occupancy:</b><br> <?php echo ucwords($recent['occupancy']).' people' ?></p>
                        <p><b class="blue-text text-darken-4">Department:</b><br> <?php echo ucwords($recent['dept']) ?></p>
                        <p><b class="blue-text text-darken-4">Approval:</b><br> <?php echo ucfirst($recent['approval']) ?></span></p>
                        <p><b class="blue-text text-darken-4">Date Approved:</b><br> <?php echo $recent['approval_date'] ?></p>
                      </div> 
                      <div class="col s12">
                        <p class="blue-text text-darken-4 wrap"><b>Comment(s):</b><br> <?php echo $recent['comments'] ?></p>
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
  
  <div class="row col s12">
    <div class="card col s12 m4 message-container">
      <nav>
        <div class="nav-wrapper yellow darken-2 col s12" style="padding-right: 0px">
          <a href="#!" class="brand-logo black-text">Feedbacks</a>
            <ul id="nav-mobile" class="right parent">
              <li><a href="" id="delete" class="spec-ajax" data-value data-query data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>" data-output=".modal-content"><i class="material-icons white-text">delete</i></a></li>
              <li><a href="" class="spec-ajax hide" data-extend-view=".send" data-parent=".parent" data-toggle="modal" data-output=".modal-content" return="true"><i class="material-icons white-text">email</i></a></li>

              <div class="send hide">
                <div class="card">
                  <div class="card-content" style="padding: 8px 0px;">
                    <div class="row">
                      <nav>
                        <div class="nav-wrapper red col s12">
                          <a href="" class="brand-logo center">Send Message</a>
                          <ul id="nav-mobile" class="right hide">
                            <li><a href="" class="spec-ajax" data-trigger="#submit"><i class="material-icons white-text">send</i></a></li>
                          </ul>
                        </div>
                      </nav>
                      <div class="col s12">
                        <form class="form" data-dest="<?php echo __url__.'/actions/messages.actions.php' ?>" data-output=".modal-content">
                          <div class="input-field">
                            <input type="text" id="to" name="to" class="autocomplete" required="true">
                            <label for="to">To</label>
                          </div>
                          <div class="input-field">
                            <input type="text" required="true" name="subject" id="subject" class="validate">
                            <label for="subject">Subject</label>
                          </div>
                          <div class="input-field">
                            <textarea name="message" id="message" required="true" placeholder="Type your message here" class="materialize-textarea"></textarea>
                            <label for="message">Message</label>
                          </div>
                          <div class="input-field center-align">
                            <input type="hidden" name="send" value="set">
                            <input type="submit" id="submit" value="SEND" class="btn red white-text">
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
              </div>
              </div>
            </ul>
        </div>
      </nav>
      <div class="card-content" style="padding: 0px;">
        <div class="row">
          <div style="padding: 5px;">
            <select class="browser-default dynamic-search" name="feed-choice" data-output=".message-summary" data-query="booksummary" required data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>">
              <option value="9">All Feedbacks</option>
              <option value="0">Unread Feedbacks</option>
              <option value="1">Read Feedbacks</option>
            </select>
          </div>
          <div class="message-summary" style="padding: 7px;">
            <?php include("./booksummary.php") ?>
          </div>
        </div>
      </div>
    </div>
    <div class="card col s12 m8 message-container">
      <div class="card-content" id="content">
        <p class="flow-text center-align grey-text" >Your Feedbacks will be displayed here!</p>
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