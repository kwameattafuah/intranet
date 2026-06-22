<?php  
	// include define
	include("../layout/definition.php");
	// include controller class
	include("../controllers/conf.controller.php");
	// initialise controller class
	$class = new Conf; 	

	if (isset($_POST['query']) && $_POST['query'] == "confbooking") { 
		$book = $class->bookfetch($_POST['value']);
	?>
      <form class="form" data-dest="<?= __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" form-type="form">
        <div>
	        <span class="flow-text">Conference Room Reservation</span>
	        <span class="right"><a href="" class="btn-floating red waves-effect spec-ajax tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete Booking" data-dest="<?php echo __url__.'/actions/conf.actions.php' ?>" data-output=".modal-content" data-query="deletebooking" id="<?= $book['id'] ?>" data-fadeOut="<?= '#booking'.$book['id'] ?>"><i class="material-icons">delete</i></a></span>
	    </div>    
        <div class="row">
          <div class="input-field">
            <input type="text" name="description" id="description" required="true" value="<?= $book['description'] ?>">
            <label for="description">Booking Description</label>
          </div>
          <div class="input-field">
            <input type="text" name="booking_by" id="booking_by" required="true" value="<?= $book['booking_by'] ?>">
            <label for="booking_by">Booking By</label>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 no-pad-left">
              <input type="date" name="sdate" id="sdate" required="true" value="<?= $book['start_date'] ?>">
              <label for="sdate"></label>
            </div>
            <div class="input-field col s12 m6 no-pad-right">
              <input type="time" name="stime" id="stime" required="true" value="<?= $book['start_time'] ?>">
              <label for="stime"></label>
            </div>
          </div>  
          <div class="row">
            <div class="input-field col s12 m6 no-pad-left">
              <input type="date" name="edate" id="edate" required="true" value="<?= $book['end_date'] ?>">
              <label for="edate"></label>
            </div>
            <div class="input-field col s12 m6 no-pad-right">
              <input type="time" name="etime" id="etime" required="true" value="<?= $book['end_time'] ?>">
              <label for="etime"></label>
            </div>
          </div>          
        </div>    
                                            
        <div class="input-field center-align">
          <input type="hidden" name="editbooking" value="<?= $book['id'] ?>">
          <button type="submit" class="waves-effect waves-light btn green darken-2" style="width: 43%">SAVE</button>
        </div>
      </form>           	

<?php }		

	if (isset($_POST['addprioritybooking'])) {
		$response = $class->bookadd($_POST['description'],$_POST['booking_by'],$_POST['sdate'],$_POST['edate'],$_POST['stime'],$_POST['etime'],$_POST['dept'],$_POST['priority']);
		if ($response !== false){
			if($response === true) { 
				echo "<p class='flow-text green-text center'>Reservation successfully created.<br>You may notify Mail Groups!</p>";
			} elseif ($response == 'wrong-timing') {
				echo "<p class='flow-text red-text center'>Wrong Timing Error:<br>Please check your start and end dates!</p>";
			} elseif ($response == 'timing-error') {
				echo "<p class='flow-text red-text center'>Time Error:<br>Please review and change your start and end timings!</p>";		
			} elseif ($response == 'timing-clash') {
				echo "<p class='flow-text red-text center'>Timing Clash Error:<br>Please review and change your timings!</p>";		
			} else {
			echo "<p class='flow-text red-text center'>An error occured.<br>Please Try Again!</p>";	
			}		
		}
	}

	if (isset($_POST['mailnotify'])) {
		$aj = 0;
		//*--- Mail Sending ---*//
		$receivers = $class->fetchmailreceiver($_POST['mgrp']);
		$bookings = $class->fetchbookings();
		$bookNum = count($bookings);

		foreach ($receivers as $receiver) {
			$emails[] = trim($receiver['email']);
		}
		$to = strip_tags(implode(",", $emails)); // multiple receivers

		$from = 'Conference Room <confroom@gacl.com.gh>'; //CONFERENCE ROOM
		$subject = 'CONFERENCE ROOM BOOKING';

		$msg = '<html><body>
					<style>
						body{font-family:Arial, "sans-serif";}
						table th{font-size: 12px;}
						.detail{font-size: 10px;}
						h4{margin-bottom: 0px;}
						.center{text-align: center;}
					</style>	
					<table border="1px" style="width: 100%; border-collapse: collapse">
						<thead>
							<th colspan="7" style="text-align: center; background-color: #003b77; padding: 7px">
								<h2 style="color: #ffffff">CONFERENCE ROOM BOOKING</h2>
								<h4 style="color: #dfdfdf; margin-top: 0px;">
								<em>GACL - Head Office</em></h4>
							</th>
						</thead>
						<tbody>
							<tr>
								<th>No.</th>
					          	<th>Description</th>
					          	<th>Booking By</th>
					          	<th>Start Date</th>
					          	<th>End Date</th>
					          	<th>Start Time</th>
					          	<th>End Time</th>
							</tr>';
						foreach ($bookings as $booking) { ++$aj;
							$msg .= '<tr>
								<td class="center detail">'.$aj.'</td>
								<td class="detail">'.$booking['description'].'</td>
								<td class="detail">'.$booking['booking_by'].'</td>
								<td class="center detail">'.date("jS M. Y", strtotime($booking['start_date'])).'</td>
								<td class="center detail">'.date("jS M. Y", strtotime($booking['end_date'])).'</td>
								<td class="center detail">'.date("H:i", strtotime($booking['start_time'])).'</td>
								<td class="center detail">'.date("H:i", strtotime($booking['end_time'])).'</td>
							</tr>';
						}							
						$msg .= '</tbody>
						<tfoot style="text-align: center">
							<tr>
								<td colspan="7" style="padding: 5px"><h4 style="color: #599d0b">Powered by Team ICT</h4></td>
							</tr>	
						</tfoot>						
					</table>
				</body></html>';

		//*--- mail sending ---*//
		if ( $class->sendmail($to,$from,$subject,$msg) )
			echo "<p class='flow-text green-text center text-darken-2'>Notifications sent for <b>".$bookNum."</b> <em>Booking(s)</em></p>";
		else 
			echo "<p class='flow-text red-text center'>An error occured.<br>Please Try Again!</p>";	
	}

	if (isset($_POST['addbooking'])) {
		$response = $class->bookadd($_POST['description'],$_POST['booking_by'],$_POST['sdate'],$_POST['edate'],$_POST['stime'],$_POST['etime'],$_SESSION['aj.gaclintra']['dept']);
		if ($response !== false){
			if($response == 1) { $aj = 0;
				//*--- Mail Sending ---*//
				$receivers = $class->fetchmailreceiver(null);
				$bookings = $class->fetchbookings();

				foreach ($receivers as $receiver) {
					$emails[] = trim($receiver['email']);
				}
				$to = strip_tags(implode(",", $emails)); // multiple receivers

				$from = 'Conference Room <confroom@gacl.com.gh>';
				$subject = 'CONFERENCE ROOM BOOKING';

				$msg = '<html><body>
							<style>
								body{font-family:Arial, "sans-serif";}
								table th{font-size: 12px;}
								.detail{font-size: 10px;}
								h4{margin-bottom: 0px;}
								.center{text-align: center;}
							</style>	
							<table border="1px" style="width: 100%; border-collapse: collapse">
								<thead>
									<th colspan="7" style="text-align: center; background-color: #003b77; padding: 7px">
										<h2 style="color: #ffffff">CONFERENCE ROOM BOOKING</h2>
										<h4 style="color: #dfdfdf; margin-top: 0px;">
										<em>GACL - Head Office</em></h4>
									</th>
								</thead>
								<tbody>
									<tr>
										<th>No.</th>
							          	<th>Description</th>
							          	<th>Booking By</th>
							          	<th>Start Date</th>
							          	<th>End Date</th>
							          	<th>Start Time</th>
							          	<th>End Time</th>
									</tr>';
								foreach ($bookings as $booking) { ++$aj;
									$msg .= '<tr>
										<td class="center detail">'.$aj.'</td>
										<td class="detail">'.$booking['description'].'</td>
										<td class="detail">'.$booking['booking_by'].'</td>
										<td class="center detail">'.date("jS M. Y", strtotime($booking['start_date'])).'</td>
										<td class="center detail">'.date("jS M. Y", strtotime($booking['end_date'])).'</td>
										<td class="center detail">'.date("H:i", strtotime($booking['start_time'])).'</td>
										<td class="center detail">'.date("H:i", strtotime($booking['end_time'])).'</td>
									</tr>';
								}							
								$msg .= '</tbody>
								<tfoot style="text-align: center">
									<tr>
										<td colspan="7" style="padding: 5px"><h4 style="color: #599d0b">Powered by Team ICT</h4></td>
									</tr>	
								</tfoot>						
							</table>
						</body></html>';
						
				//*--- mail sending ---*//
				if ( $class->sendmail($to,$from,$subject,$msg) )
					echo "<p class='flow-text green-text center'>Reservation successfully created.</p>";

			} elseif ($response == 'wrong-timing') {
				echo "<p class='flow-text red-text center'>Wrong Timing Error:<br>Please check your start and end dates!</p>";
			} elseif ($response == 'timing-error') {
				echo "<p class='flow-text red-text center'>Time Error:<br>Please review and change your start and end timings!</p>";		
			} elseif ($response == 'timing-clash') {
				echo "<p class='flow-text red-text center'>Timing Clash Error:<br>Please review and change your timings!</p>";		
			} else {
			echo "<p class='flow-text red-text center'>An error occured.<br>Please Try Again!</p>";	
			}
		}		
	}

	if (isset($_POST['editbooking'])) {
		$response = $class->bookedit($_POST['description'],$_POST['booking_by'],$_POST['sdate'],$_POST['edate'],$_POST['stime'],$_POST['etime'],$_POST['editbooking']);
		if ($response !== false) {
			if($response == 1) { $aj = 0;
				//*--- Mail Sending ---*//
				$receivers = $class->fetchmailreceiver(null);
				$bookings = $class->fetchbookings();

				foreach ($receivers as $receiver) {
					$emails[] = trim($receiver['email']);
				}
				$to = strip_tags(implode(",", $emails)); // multiple receivers

				$from = 'Conference Room <confroom@gacl.com.gh>';
				$subject = 'CONFERENCE ROOM BOOKING';

				$msg = '<html><body>
							<style>
								body{font-family:Arial, "sans-serif";}
								table th{font-size: 12px;}
								.detail{font-size: 10px;}
								h4{margin-bottom: 0px;}
								.center{text-align: center;}
							</style>	
							<table border="1px" style="width: 100%; border-collapse: collapse">
								<thead>
									<th colspan="7" style="text-align: center; background-color: #003b77; padding: 7px">
										<h2 style="color: #ffffff">CONFERENCE ROOM BOOKING</h2>
										<h4 style="color: #dfdfdf; margin-top: 0px;">
										<em>GACL - Head Office</em></h4>
									</th>
								</thead>
								<tbody>
									<tr>
										<th>No.</th>
							          	<th>Description</th>
							          	<th>Booking By</th>
							          	<th>Start Date</th>
							          	<th>End Date</th>
							          	<th>Start Time</th>
							          	<th>End Time</th>
									</tr>';
								foreach ($bookings as $booking) { ++$aj;
									$msg .= '<tr>
										<td class="center detail">'.$aj.'</td>
										<td class="detail">'.$booking['description'].'</td>
										<td class="detail">'.$booking['booking_by'].'</td>
										<td class="center detail">'.date("jS M. Y", strtotime($booking['start_date'])).'</td>
										<td class="center detail">'.date("jS M. Y", strtotime($booking['end_date'])).'</td>
										<td class="center detail">'.date("H:i", strtotime($booking['start_time'])).'</td>
										<td class="center detail">'.date("H:i", strtotime($booking['end_time'])).'</td>
									</tr>';
								}							
								$msg .= '</tbody>
								<tfoot style="text-align: center">
									<tr>
										<td colspan="7" style="padding: 5px"><h4 style="color: #599d0b">Powered by Team ICT</h4></td>
									</tr>	
								</tfoot>						
							</table>
						</body></html>';

				//*--- mail sending ---*//
				if ( $class->sendmail($to,$from,$subject,$msg) )
					echo "<p class='flow-text green-text center'>Reservation details successfully updated.</p>";

			} elseif ($response == 'wrong-timing') {
				echo "<p class='flow-text red-text center'>Wrong Timing Error:<br>Please check your start and end dates!</p>";
			} elseif ($response == 'timing-error') {
				echo "<p class='flow-text red-text center'>Time Error:<br>Please review and change your start and end timings!</p>";		
			} elseif ($response == 'timing-clash') {
				echo "<p class='flow-text red-text center'>Timing Clash Error:<br>Please review and change your timings!</p>";		
			} else {
			echo "<p class='flow-text red-text center'>An error occured.<br>Please Try Again!</p>";	
			}				
		} var_dump($response);
	}

	if (isset($_POST['query']) && $_POST['query'] == "deletebooking") { 
		$result = $class->delreservation($_POST['id']);

		if ($result === false) {
			echo '<h4 class="red-text center">An unexpected error occured!</h4>';
		}else{
			echo '<h4 class="brown-text center">Booking successfully deleted!</h4>';
		}
	}

	if (isset($_POST['deletemgroup'])) { 
		$result = $class->delmgroup($_POST['delgroup'],$_SESSION['aj.gaclintra']['id']);

		if ($result === false) {
			echo '<h6 class="red-text center">An unexpected error occured!</h6>';
		}else{
			echo '<h6 class="brown-text center">Mail Group successfully deleted!</h6>';
		}
	}	

	if (isset($_POST['query']) && $_POST['query'] == "receiverdelete") { 
		$result = $class->delreceiver($_POST['id'],$_SESSION['aj.gaclintra']['id']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 5000)</script>';
		}else{
			echo '<script>Materialize.toast("Recepient successfully deleted!", 5000)</script>';
		}
	}	

  if (isset($_POST['addreceiver'])){
    $result = $class->receiveradd($_POST['name'],$_POST['email'],$_POST['group'],$_SESSION['aj.gaclintra']['id']);
    if ($result === false) {
      echo '<p class = "red-text flow-text center"> An unexpected error occured! </p>';
    }else{
      echo '<p class = "green-text flow-text center"> New Recepient successfully added! </p>';
    }
  }

  if (isset($_POST['editreceiver'])){
    $result = $class->receiveredit($_POST['name'],$_POST['email'],$_POST['group'],$_POST['editreceiver']);
    if ($result === false) {
      echo '<p class = "red-text flow-text center"> An unexpected error occured! </p>';
    }else{
      echo '<p class = "green-text flow-text center"> Recepient Information successfully updated! </p>';
    }
  }

  if (isset($_POST['newmailgroup'])){
    $result = $class->createmailgroup($_POST['name'],$_SESSION['aj.gaclintra']['id']);
    if ($result === false) {
      echo '<p class = "red-text flow-text center"> An unexpected error occured! </p>';
    }else{
      echo '<p class = "green-text flow-text center"> Mail Group successfully created! </p>';
    }
  }	

	// --- LOGOUT --- //
	if (isset($_POST['query']) && $_POST['query'] == "logout"){
		echo '<script>window.close();</script>';
	}
 ?>

