<?php  
	// include define
	include("../layout/definition.php");
	// include controller class
	include("../controllers/ldcbook.controller.php");
	// initialise controller class
	$class = new Ldc;


// SEND FEEDBACK
	if (isset($_POST['feed'])){
		$result = $class->feed($_POST['feed'],$_POST['details'],$_SESSION['aj.ldc']['ldcid']);
		if ($result === false) {
			echo '<p class = "red-text flow-text center"> An unexpected error occured! </p>';
		}else{
			echo '<p class = "green-text flow-text center"> Feedback sent successfully! </p>';
		}
	}

// READ FEEDBACK
	if (isset($_POST['query']) && $_POST['query'] === 'readfeed' ) {
		$info = $class->readfeed($_POST['id'],$_SESSION['aj.ldc']['ldcid']);
	 ?>
		<div class="message-content row">
 			<p class="flow-text bold green-text text-darken-4"><?php echo ucwords($info['subject']) ?></p>
 			<hr class="divider">
 			<div class="message-headers" style="padding-bottom: 5px">
 				<p><?php echo ucwords($info['by_whom']). ' | <span class="blue-text">'.$info['dept'] ?></span></p>
 				<p><span class="blue-text text-darken-2">Room: </span><?php echo ucwords($info['room_name']) ?></p>
 				<p class="grey-text"><?php echo date("jS M, Y @ H:s", strtotime($info['when_was'])).' GMT'; ?></p>
 			</div>
 			<div class="divider"></div>
 			<div style="padding-top: 20px"><p class="wrap"><?php echo ucfirst($info['details']); ?></p></div>
		</div>
<?php	}

// AUTHORISE RESERVATION	
	if (isset($_POST['query']) && $_POST['query'] === 'authorise'){
		$result = $class->authorise($_SESSION['aj.ldc']['ldcid'],$_POST['id'],$_POST['value']);
		if ($result === false) {
			echo '<p class="red-text center">Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Authorisation successful</p>';
		}
	}


// EDIT ROOM
	if (isset($_POST['editroom'])) {
		$result = $class->roomupdate($_POST['title'],$_POST['venue'],$_POST['occupancy'],$_POST['editroom']);
		if ($result === false) {
			echo '<p class="red-text center">Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Room updated successfully</p>';
		}
	}


// ADD ROOM
	if (isset($_POST['addroom'])) {
		$result = $class->roomadd($_POST['title'],$_POST['venue'],$_POST['occupancy']);
		if ($result === false) {
			echo '<p class="red-text center">An Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Room added successfully</p>';
		}
	}


// DELETE ROOM
	if (isset($_POST['query']) && $_POST['query'] === 'roomdelete') {
		$result = $class->roomdel($_POST['id'],$_SESSION['aj.ldc']['ldcid']);
		if ($result === false) {
			echo '<script>Materialize.toast("An Error Occured. Please Try Again!", 2000)</script>';	
		}else{
			echo '<script>Materialize.toast("Room deleted successfully!", 2000)</script>';
		}
	}	


// VIEW FEEDBACK	
	if (isset($_POST['query']) && $_POST['query'] == "viewfeed") {
		$info = $class->viewfeed($_POST['id']);
?>
		<div class="message-content row">
 			<p class="flow-text bold green-text text-darken-4"><?php echo ucwords($info['subject']) ?></p>
 			<hr class="divider">
 			<div class="message-headers" style="padding-bottom: 5px">
 				<p><?php echo ucwords($info['by_whom']). ' | <span class="blue-text">'.$info['dept'] ?></span></p>
 				<p><span class="blue-text text-darken-2">Room: </span><?php echo ucwords($info['room_name']) ?></p>
 				<p class="grey-text"><?php echo date("jS M, Y @ H:s", strtotime($info['when_was'])).' GMT'; ?></p>
 			</div>
 			<div class="divider"></div>
 			<div style="padding-top: 20px"><p class="wrap"><?php echo ucfirst($info['details']); ?></p></div>
		</div>
<?php
	}

// DELETE FEEDBACK	
	if (isset($_POST['query']) && ($_POST['query'] == "delfeed")) {
		$result = $class->delfeed($_POST['value']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!",4000)</script>';
		}else{
			echo '<script>Materialize.toast("Deleted!",4000)</script>';
		}
	}


// FEEDBACK SUMMARY
	if (isset($_POST['search']) && $_POST['id'] === 'booksummary') {
		// search		
		if($_POST['search'] != 9)
			$feedbacks = $class->feedbacks($_POST['search']);
		else
			$feedbacks = $class->feedbacks(null);

		// include display
		include("../views/ldc/admin/booksummary.php");
	}


// SAVE RESERVATION
	if (isset($_POST['booksave'])) {
		list($code,$result) = $class->booksave($_POST['purpose'],$_POST['booked_by'],$_POST['dept'],$_POST['room'],$_POST['occupancy'],$_POST['start_date'],$_POST['end_date']);
		if ($result === false) {
			echo '<p class="red-text center">An Error Occured. Please Try Again!</p>';
		}else{
			echo '<p class="green-text center flow-text">Reservation Code: '.$code.'</p>';
		}
	}


// ADMIN BOOKING SEARCH
	if (isset($_POST['bookSearch'])) {
		// search		
		if(!empty(trim($_POST['item'])))
			$flags = $class->booksearch($_POST['item']);
		else
			$flags = $class->flags(null);

		// include display
		include("../views/ldc/admin/flags.php");
	}


// USER BOOKING SEARCH
	if (isset($_POST['requisitionSearch'])) {	
		// search		
		$val = trim($_POST['itemcode']);
		$room = $class->requisitionsearch($val);

		// include display
		include("../views/ldc/rooms/room.php");
	}	


// ANSWER A BOOKING
	if (isset($_POST['bookAnswer'])) {
		$result = $class->bookanswer($_POST['answer'],$_POST['message'],$_POST['bookAnswer'],$_SESSION['aj.ldc']['ldcid']);
		if ($result === false) {
			echo '<p class="red-text center">An Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Approval successfully confirmed</p>';
		}
	}


// CHECK FOR FLAGS AND DISPLAY
	if (isset($_POST['query']) && $_POST['query'] == "flag") {
		$info = $class->flags($_POST['value']);
		if($info['flag'] == 'C'){
			$flagged = '<span class="red-text">Clash Timing</span>';
		}elseif($info['flag'] == 'S'){
			$flagged = '<span class="red-text text-darken-1">Occupancy Overload</span>';
		}else{
			$flagged ='<span class="grey-text">N/A</span>';
		}

		if($info['status'] == 1){
			$state = '<span class="green-text">Approved</span>';
		}elseif($info['status'] == 0){
			$state = '<span class="red-text text-darken-1">Denied</span>';
		}else{
			$state ='<span class="grey-text">Pending</span>';
		}	
?>
		<div class="message-content row">
 			<p class="flow-text bold green-text text-darken-4"><?php echo ucwords($info['purpose']) ?></p>
 			<hr class="divider">
 			<div class=" row">
              <div class="col s12 m6">
              	<p><b class="blue-text text-darken-4">Reservation Code:</b><br><span class="brown-text text-darken-2"><?php echo $info['code'] ?></span></p>
                <p><b class="blue-text text-darken-4">Room:</b><br> <?php echo ucwords($info['room_name']) ?></p>
                <p><b class="blue-text text-darken-4">Start Date/Time:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($info['start_dt'])).' GMT'; ?></p>
                <p><b class="blue-text text-darken-4">End Date/Time:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($info['end_dt'])).' GMT'; ?></p>
                <p><b class="blue-text text-darken-4">Occupancy:</b><br> <?php echo $info['occupancy'].' people' ?></p>
              </div>  
              <div class="col s12 m6">
              	<p><b class="blue-text text-darken-4">Booked By:</b><br> <?php echo ucfirst($info['booked_by']) ?></p>
                <p><b class="blue-text text-darken-4">Department:</b><br> <?php echo ucwords($info['dept']) ?></p>
                <p><b class="blue-text text-darken-4">Date Booked:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($info['book_date'])).' GMT'; ?></p>
                <p><b class="blue-text text-darken-4">Approval:</b><br> <?= (!is_null($info['approval']))? '<span class="green-text">'.$info['approval'].'' : '<span class="red-text"> Not Yet' ?></span></p>
                <p><b class="blue-text text-darken-4">Status:</b><br> <?= $state ?> </span></p>
                <p><b class="blue-text text-darken-4">Flag:</b><br> <?= $flagged ?> </span></p>    
              </div> 
              <div class="col s12">
                <p class="blue-text text-darken-4 wrap"><b>Comment(s):</b><br> <?php echo $info['comments'] ?></p>
              </div> 
            </div>
 			<div class="divider"></div>
 				<form class="form" data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>" data-output=".modal-content" form-type="dynamic" >
					<div class="input-field">
    					<textarea name="message" id="message" placeholder="Type your comment here" class="materialize-textarea"></textarea>
    					<label for="message">Comments</label>
    				</div>
    				<div class="row">
	  			        <div class="input-field col s12 m9 no-pad-left">
						    <select class="browser-default" name="answer" required>
						      	<option value="" disabled selected>Choose Approval</option>
						      	<option value="1" >Approved</option>
								<option value="0" >Denied</option>
						    </select>
			        	</div>
						<div class="input-field col s12 m3 center-align">
			      			<input type="hidden" name="bookAnswer" value="<?= $info['id'] ?>">
						    <button type="submit" id="submit" class="btn green darken-2 white-text"> CONFIRM </button> 
						</div>  					
    				</div>	
			    </form>
 			</div>
		</div>
<?php
	}			

?>

