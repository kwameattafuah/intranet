<?php  
	// include define
	include("../layout/definition.php");
	// include controller class
	include("../controllers/ldc.controller.php");
	// initialise controller class
	$class = new Ldc;


// SUBMIT COURSE EVALUATION
	if (isset($_POST['courseevaluation'])) {
		$thecourse = $_POST['courseevaluation'];
		$score = false;
		foreach ($_POST as $key => $value) {
			if ($key != 'courseevaluation') {
				$thesection = (substr($key, 0, strpos($key, '-')));
				$thequestion = (substr($key, strpos($key, '-') + 1));
				$theplace = $value;
			}
			if ($thesection != 'comments')	
				$score = $class->scorecourse($thecourse,$thesection,$thequestion,$theplace);
			else
				$score = $class->remarkcourse($thecourse,$thesection,$thequestion,$theplace,$_SESSION['aj.ldc']['ldcid']);
		}
		if ($score !== false)
			echo '<p class="center-align flow-text green-text">Course Evaluated Successfully!</p>';
		else
			echo '<p class="center-align flow-text red-text">An unexpected error occured.<br>Please Try again later!</p>';
	}


// SUBMIT PARTICIPANT APPRAISAL
	if (isset($_POST['participantappraisal'])) {
		$person = $_SESSION['aj.ldcappraisal']['appraisee'];
		$name = $_SESSION['aj.ldcappraisal']['name'];
		$mail = $_SESSION['aj.ldcappraisal']['mail'];
		$thecourse = $_SESSION['aj.ldcappraisal']['key'];
		foreach ($_POST as $key => $value) {
			if ($key != 'participantappraisal') {
				$thesection = (substr($key, 0, strpos($key, '-')));
				$thequestion = (substr($key, strpos($key, '-') + 1));
				$theplace = $value;
			}
			if ($thesection != 'remarks')	
				$score = $class->scoreappraisee($thecourse,$thesection,$thequestion,$theplace,$person,$name.' {'.$mail.'}');
			else
				$score = $class->remarkappraisee($thecourse,$thesection,$thequestion,$theplace,$person,$name.' {'.$mail.'}');
		}
		if ($score !== false) {
			echo '<p class="center-align flow-text green-text text-darken-1">You have successfully appraised <b>'.$_SESSION['aj.ldcappraisal']['person'].'</b>.</p>';
			$_SESSION['aj.ldcappraisal'] = true;
		} else {
			echo '<p class="center-align flow-text red-text">An unexpected error occured.<br>Please Try again later!</p>';
		}
	}	


// LOAD PARTICIPANT APPRAISAL
	if (isset($_POST['appraisal'])) {
		$_SESSION['aj.ldcappraisal']['participant'] = $_POST['person'];
		echo '<script> window.location="'.__url__.'/ldc/evaluations/" </script>';
	}


// CHANGE PASSWORD
	if (isset($_POST['pass'])) {
		if ($_POST['npass'] != $_POST['rpass']) {
			echo '<p class="center-align flow-text">Passwords don\'t match</p>';
			return ;
		}

		$result = $class->pass($_POST['password'],$_POST['npass'],$_SESSION['aj.ldc']['ldcid']);

		if ($result === true)
			echo '<p class="center-align flow-text green-text">Password changed Successfully</p>';
		else
			echo '<p class="center-align flow-text red-text">Please check your entries!</p>';
	}


// SEND FEEDBACK
	if (isset($_POST['feed'])){
		$result = $class->feed($_POST['feed'],$_POST['details'],$_SESSION['aj.ldc']['ldcid']);
		if ($result === false) {
			echo '<p class = "red-text flow-text center"> An unexpected error occured! </p>';
		}else{
			echo '<p class = "green-text flow-text center"> Feedback sent successfully! </p>';
		}
	}


// TAKE A COURSE
	if (isset($_POST['learn'])){
		$result = $class->takecourse($_POST['course'],$_SESSION['aj.ldc']['ldcid']);
		if ($result === false) {
			echo '<p class = "red-text flow-text center"> An unexpected error occured! </p>';
		}else{
			echo '<p class = "green-text flow-text center"> Course Requested Successfully! </p>';
		}
	}	


// READ FEEDBACK
	if (isset($_POST['query']) && $_POST['query'] === 'readfeed' ) {
		$info = $class->readfeed($_POST['id'],$_SESSION['aj.ldc']['ldcid']);
	 ?>
		<div class="message-content row">
 			<p class="flow-text bold green-text text-darken-4"><?php echo $info['title']; ?></p>
 			<hr class="divider">
 			<div class="message-headers row">
 				<p class="blue-text text-darken-4"><?php echo $info['name'] ?></p>
 				<p class="grey-text"><?php echo date("jS M, Y @ H:s", strtotime($info['when_was'])).' GMT'; ?></p>
 			</div>
 			<div><p class="wrap"><?php echo ucfirst($info['details']); ?></p></div>
		</div>

<?php	}

// VIEW COURSE
	if (isset($_POST['query']) && $_POST['query'] === 'viewcourse' ) {
		$info = $class->viewcourse($_POST['id']);
	 ?>
		<div class="info-details">
		    <div class="row">
		      <div class="col s12">
		          <div class="card-content">
		            <h4><?php echo ucwords($info['title']) ?></h4>
		            <div class="divider"></div>
		            	<p><b class="green-text text-darken-3">Start Date:</b> <?php echo date("l, jS F, Y", strtotime($info['start_date'])) ?></p>
                        <p><b class="green-text text-darken-3">End Date:</b> <?php echo date("l, jS F, Y", strtotime($info['end_date'])) ?></p>
                        <p><b class="green-text text-darken-3">Venue:</b> <?php echo $info['room_name'] ?></p>
                        <p><b class="green-text text-darken-3">Status:</b> <?php echo $info['registered'].' of '.$info['capacity'] ?></p>
		          </div>
		      </div>
		    </div>
		</div>

<?php }

// ACTIVATE COURSE FOR EVALUATION
	if(isset($_POST['table']) && $_POST['table'] == "activatecourse"){
		$result = $class->evaluatecourse($_POST['id']);

		if ($result === true)
			echo '<script>Materialize.toast("Course Evaluation Status changed!", 4000)</script>';
		else
			echo '<script>Materialize.toast("An unexpected error occured!", 5000)</script>';
		exit();
	}


// APPRAISAL REMARKS
	if (isset($_POST['appraisalremark'])) {
		$result = $class->feed($_POST['appraisalremark'],$_POST['details'],$_SESSION['aj.ldc']['ldcid']); 

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Remarks successfully added!</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured, Please Try Again!</p>';
	}


// UPDATE PARTICIPANT INFORMATION
	if (isset($_POST['uppart_account'])) {
		$check = $class->user_pass_verify($_POST['password'],$_SESSION['aj.ldc']['ldcid']);

		if ($check !== false) {
			$result = $class->editparticipant($_POST['name'],$_POST['position'],$_POST['staffid'],$_POST['phone'],$_SESSION['aj.ldc']['ldcid']);

			if ($result === true)
				echo '<p class="center-align green-text flow-text">User Information updated Successfully</p>';
			else
				echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
		}else{
			echo '<p class="center-align red-text flow-text">Password provided is Incorrect!</p>';	
		}
	}


// VIEW USER PROFILE
	if (isset($_POST['query']) && $_POST['query'] === 'participant_profile'){ 
		$account = $class->participant_account($_POST['value']);
?>
		<h4 class="blue-text text-darken-2">My Profile</h4>
        <form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" form-type="form">
          <div class="input-field">
            <input type="text" id="name" name="name" required="true" value="<?= $account['name'] ?>">
            <label for="name">Name</label>
          </div>    
          <div class="input-field">
            <input type="text" name="position" id="position" required="true" value="<?= $account['position'] ?>">
            <label for="position">Position</label>
          </div> 
          <div class="input-field">
            <input type="text" name="staffid" id="staffid" required="true" value="<?= $account['staff_id'] ?>">
            <label for="staffid">Staff Id</label>
          </div>         
          <div class="input-field">
          	<span>Email</span>
            <input type="email" id="email" name="email" required="true" value="<?= $account['email'] ?>">
          </div>
          <div class="input-field">
            <input type="text" name="phone" id="phone" required="true" value="<?= $account['phone'] ?>">
            <label for="phone">Phone</label>
          </div>
          <div class="input-field">
            <input type="password" id="password" name="password" required="true">
            <label for="password">Password</label>
          </div>   
          <div class="input-field center-align">
            <input type="hidden" name="uppart_account" value="set">
            <input type="submit" value="UPDATE" class="btn indigo">
          </div>
        </form>

<?php	}

// ATTENDANCE SEARCH
	if (isset($_POST['search']) &&  $_POST['id'] == "search") {
		$date = $_POST['search'];
		if ($date == "all") { 
			list($null,$regs) = $class->attendance();
		}else{
			$regs = $class->attended($date);
		}

		 $no = 0; foreach($regs as $reg) {?>
          <tr>
            <td class="center"><?= ++$no.'.' ?></td>
            <td><?= $reg['name'] ?></td>
            <td><?= $reg['dept'] ?></td>
            <td><?= $reg['title'] ?></td>
            <td class="center"><?= ($reg['status'] !== 0)? '<span class="green-text"> Present' : '<span class="red-text"> Absent' ?></span></td>
          </tr>
  <?php }  
	}	

// AUTHORISE PARTICIPANT
	if (isset($_POST['authorise'])) {
		$result = $class->authorise($_SESSION['aj.ldc']['ldcid'],$_POST['authorise'],$_POST['thecourse'],$_POST['answer'],$_POST['message']);
		if ($result === false) {
			echo '<p class="red-text center">Error Occured. Please Try Again!</p>';	
		}else{
			//--- mail sending ---//
				$info = $class->attendeeview($_POST['authorise'],$_POST['thecourse']);
				$from = 'GACL LEARNING & DEVELOPMENT CENTRE <no-reply-ldc@gacl.com.gh>';
				$to = strip_tags(trim($info['email']));

				if ($_POST['answer'] == '1') {
					$subject = 'TRAINING APPROVAL'; 
					$msg = ' <html><body><div style="width: 50%; margin: 0px auto;"><div style="border-bottom: #003b77 solid 2px; text-align: center;">
						<h3>LDC REGISTRATION RESPONSE</h3></div><div style="background-color: #bbe25c; padding-left: 7%">	
						<table cellpadding="10">
							<tr>
								<td colspan="2">Dear '.ucwords(strtolower($info['name'])).',<br><br> you have successfully registered and enrolled for <b>'.strtoupper($info['title']).'</b>.<br> You have been approved to take the course which spans from <b>'.date('l jS F, Y',strtotime($info['start_date'])).'</b> to <b>'.date('l jS F, Y',strtotime($info['end_date'])).'</b>.<br>Below, are your enrollment details.Thank You!</td>
							</tr>	
							<tr>
								<td><b>Name</b></td>
								<td>'.$info['name'].'</td>
							</tr>
							<tr>
								<td><b>Staff Id</b></td>
								<td>'.$info['staff_id'].'</td>
							</tr>			
							<tr>
								<td><b>Position</b></td>
								<td>'.$info['position'].'</td>
							</tr>
							<tr>
								<td><b>Course</b></td>
								<td>'.$info['title'].'</td>
							</tr>
							<tr>
								<td><b>Email</b></td>
								<td>'.$info['email'].'</td>
							</tr>
							<tr>
								<td><b>Telephone</b></td>
								<td>'.$info['phone'].'</td>
							</tr>
							<tr>
								<td><b>Status</b></td>
								<td>Approved</td>
							</tr>
							<tr>
								<td><b>Remarks</b></td>
								<td>'.$info['comment'].'</td>
							</tr>
						</table></div></div></body></html>';
			}else{
				$subject = 'TRAINING DISAPPROVAL'; 
				$msg = '<html><body><div style="width: 50%; margin: 0px auto;">
		<div style="border-bottom: #003b77 solid 2px; text-align: center;">
			<h3 style="color: red">LDC REGISTRATION RESPONSE</h3>
		</div><div>	
			<p>Dear '.$info['name'].',<br><br> we are sorry to inform you that your application to enrol for the course, <b>'.$info['title'].'</b> has been declined.<br><br>Thank you for your cooperation!</p>	
		</div></div></body></html>';
			}				
			//--- mail sending ---//
			if ( $class->sendmail($to,$from,$subject,$msg) )	
				echo '<p class="green-text center flow-text">Authorisation successfully confirmed</p>';
		}
	}


// EDIT COURSE
	if (isset($_POST['editcourse'])) {
		if(isset($_POST['status'])){
			$status = '1';
		}else{
			$status = '0';
		}
		$result = $class->courseupdate($_POST['title'],$_POST['instructor'],$_POST['venue'],$_POST['start_date'],$_POST['end_date'],$_POST['reg_start'],$_POST['reg_end'],$_POST['occupancy'],$status,$_POST['editcourse']);
		if ($result === false) {
			echo '<p class="red-text center">Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Course updated successfully</p>';
		}
	}


// ADD COURSE
	if (isset($_POST['addcourse'])) {
		//appraisal key
		$key = $class->rands($_POST['title']);

		if(isset($_POST['status'])){
			$status = '1';
		}else{
			$status = '0';
		}
		
		$result = $class->courseadd($_POST['title'],$_POST['instructor'],$_POST['venue'],$_POST['start_date'],$_POST['end_date'],$_POST['reg_start'],$_POST['reg_end'],$_POST['occupancy'],$status,$key,$_SESSION['aj.ldc']['ldcid']);
		if ($result === false) {
			echo '<p class="red-text center">An Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Course added successfully</p>';
		}
	}


// DELETE COURSE
	if (isset($_POST['query']) && $_POST['query'] === 'coursedelete') {
		$result = $class->coursedel($_POST['id'],$_SESSION['aj.ldc']['ldcid']);
		if ($result === false) {
			echo '<script>Materialize.toast("An Error Occured. Please Try Again!", 2000)</script>';	
		}else{
			echo '<script>Materialize.toast("Course deleted successfully!", 2000)</script>';
		}
	}	


// VIEW PARTICIPANT
	if (isset($_POST['query']) && $_POST['query'] === 'attendee'){
		$info = $class->viewattendee($_POST['value']);
		if ($info['status'] == 1) { 
			$status = '<span class="green-text"> Approved </span>'; 
		} elseif ($info['status'] == -1) { 
			$status = '<span class="red-text"> Denied </span>'; 
		}else{ 
			$status = '<span class="brown-text"> Pending </span>'; 
		}
		 ?>
		<div class="info-details">
		    <div class="row">
		      <div class="col s12">
		        <div class="card-content">
		            <h4 class="green-text text-darken-4"><?php echo $info['name'] ?></h4>
		            <small class="blue-text text-darken-2"><?= date("jS M, Y", strtotime($info['date_reg'])) ?></small>
		            <div class="divider"></div>
		            <div class="row">
		            <p class="col s12 m6"><b>Gender: </b> <?php echo ($info['sex']==='M')? 'Male' : 'Female' ?><br>
		            	<b>Position: </b><?php echo $info['position'] ?><br>
		            	<b>Staff ID: </b><?php echo $info['staff_id'] ?><br>
		            	<b>Email: </b><?php echo $info['email'] ?><br>
		            	<b>Phone: </b><?php echo $info['phone'] ?>	
		            </p>
		            <p class="col s12 m6"><b>Course: </b><?php echo $info['title'] ?><br>
		            	<b>Status: </b><?= $status ?><br>
		            	<b>Authorisation: </b><?php echo (!is_null($info['authorised_id']) )? '<span>'.$info['author'].'</span>' : '<span class="red-text">Pending</span>' ?><br>
		            	<b>Remark(s):</b> <?php echo (!empty($info['comment'])) ? $info['comment'] : '<span class="grey-text"> N/A </span>' ?>
		            </p>
		          </div>
		      	</div>
		      	<div class="card-action center <?php echo (!is_null($info['authorised_id']) )? 'hide' : '' ?> ">
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
                          <input type="hidden" name="authorise" value="<?= $info['individual_id'] ?>">
                          <input type="hidden" name="thecourse" value="<?= $info['courseid'] ?>">
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

<?php }		

// VALIDATE APPRAISAL
	if (isset($_POST['appraisalvalidate'])){
		$person = $class->fetch_appraisee_name($_POST['appraisee']);
		$_SESSION['aj.ldcappraisal'] =
		  array(
		    "name" => trim($_POST['fullname']),
		    "mail" => trim($_POST['email']),
		    "key" => trim($_POST['appraisalkey']),
		    "appraisee" => $person['email'],
		    "person" => trim(ucwords(strtolower($person['name'])))
		 ); ?>	
		 <p class="center-align">Thank you <?= ucwords(strtolower($_SESSION['aj.ldcappraisal']['name'])) ?> for deciding to appraise <b><?= $_SESSION['aj.ldcappraisal']['person'] ?></b>. <br><a href="<?= __url__.'/ldc/evaluations/?appraisalconfirmed' ?>" >Click here to begin</a> </p>
<?php 	}

// APPRAISAL DEPARTMENT CHECK
	if (isset($_POST['id']) && $_POST['id'] === 'appraisaldept'){
		// search		
		if (!empty(trim($_POST['bsearch']))) {
			$persons = $class->fetch_eval_appraisees($_POST['bsearch'],$_POST['asearch']);	?>
		 <select required="true" class="browser-default" name="appraisee" >
	      <option value="" disabled selected>select a Participant</option>
	    <?php 
			if ($persons !== false)
				foreach ($persons as $person) {
		?> 
	      <option value="<?= $person['individual']?>"><?= $person['name'] ?></option>
	      <?php } ?>
	    </select>	
	<?php	} else {
			echo '<p class="red-text center-align"> Please enter the <b>Appraisal Key</b> and Re-select your department! </p>';
		}		
	}


// FETCH TO BE APPRAISED
	if (isset($_POST['search']) && $_POST['id'] === 'appraisedparticipants') {
		$participants = $class->appraised_participants($_POST['search']);

		// include display
		include("../views/ldc/admin/appraisal.php");
	}


// VIEW COURSE ATTENDEES
	if (isset($_POST['search']) && $_POST['id'] === 'course') {
		// search		
		if($_POST['search'] != 0)
			$peeps = $class->attendees($_POST['search']);
		else
			$peeps = $class->attendees(null);

		// include display
		include("../views/ldc/admin/attendeex.php");
	}


// FILTER COURSE ATTENDEES
	if (isset($_POST['id']) && $_POST['id'] === 'course-status') {
		// search		
		if (!is_null($_POST['bsearch']) ){
			if ($_POST['asearch'] == 9) {
				$peeps = $class->attendees($_POST['bsearch']);
			}else{
				$peeps = $class->attendees_filter($_POST['bsearch'],$_POST['asearch']);
			}	
		} else {
			$peeps = $class->attendees(null);
		}

		// include display
		include("../views/ldc/admin/attendeex.php");
	}


// DISPLAY COURSE MATERIAL(S)
	if (isset($_POST['search']) && $_POST['id'] === 'coursematerial') {
		$materials = $class->materials($_POST['search']);
		if (!empty($materials)) { 
			$j = 0;
			foreach ($materials as $material) { ++$j; ?>
				<tr>
					<td><?= $j.'.' ?></td>
					<td><?= $material['name'] ?></td>
					<td><?= date('j / M / y', strtotime($material['date_modified'])) ?></td>
					<td class="right">
					 	<span><a href="<?= __docs__.'/study_materials/'.$material['course_id'].'/'.$material['source'] ?>" target="_new" download="<?= $material['name'] ?>"><i class="material-icons">input</i></a></span>
            			<span class="waves-effect spec-ajax <?= (!isset($_SESSION['aj.ldc']['ldcpos']))? 'hide':'' ?>" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" id="<?= $material['id'] ?>" data-query="materialdelete" data-fadeOut=".material"><i class="material-icons">delete</i></span>
            		</td>
				<tr>
		<?php } } else { ?>
			<tr>
				<td colspan="4" class="center grey-text">No Study Material(s) Available! </td>
			<tr>
		<?php }
	}


// ADD DOCUMENT
	if(isset($_POST['docadd'])){
		$result = $class->adddoc($_FILES['document'],$_POST['course'],$_POST['docadd']);
		if ($result === false) {
			echo '<p class="flow-text center-align red-text">An unexpected error occured! Please try again</p>';
		}else{
			echo '<p class="flow-text center-align green-text">Course Material(s) added Successfully</p>';
		}
	}			


// PARTICIPANT ATTENDANCE
	if (isset($_POST['search']) && $_POST['id'] === 'attendance') {
		// search		
		if($_POST['search'] != 0)
			$regs = $class->attended($_POST['search']);
		else
			$regs = $class->attended(null);

		// include display
		include("../views/ldc/admin/attendancee.php");
	}	


// VIEW FEEDBACK
	if (isset($_POST['query']) && $_POST['query'] == "viewfeed") {
		$message = $class->viewfeed($_POST['id']);
?>
		<div class="message-content row">
 			<p class="flow-text bold green-text text-darken-4"><?php echo $message['title']; ?></p>
 			<hr class="divider">
 			<div class="message-headers row">
 				<p class="blue-text text-darken-4"><?php echo $message['name'] ?></p>
 				<p class="grey-text"><?php echo date("jS M, Y @ H:s", strtotime($message['when_was'])).' GMT'; ?></p>
 			</div>
 			<div><p class="wrap"><?php echo ucfirst($message['details']); ?></p></div>
		</div>

<?php
	}

// MATERIAL DELETE
	if (isset($_POST['query']) && ($_POST['query'] == "materialdelete")) {
		$result = $class->delmaterial($_POST['id'],$_SESSION['aj.ldc']['ldcid']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!",4000)</script>';
		}else{
			echo '<script>Materialize.toast("Material Deleted!",4000)</script>';
		}
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
	if (isset($_POST['search']) && $_POST['id'] === 'msummary') {
		// search		
		if($_POST['search'] != 9)
			$feedbacks = $class->feedbacks($_POST['search']);
		else
			$feedbacks = $class->feedbacks(null);

		// include display
		include("../views/ldc/admin/msummary.php");
	}


// LDC ATTENDANCE WINDOW
	if (isset($_POST['reggen'])) {
		if(!isset($_SESSION['aj.ldc']['course']) || $_SESSION['aj.ldc']['course'] != $_POST['course'] ){
			$state = $class->reggen($_POST['course'],$_POST['passphrase'],$_SESSION['aj.ldc']['ldcid']);
		}else{
			$state = true;
		}

		if ($state === false){
			echo '<p class="red-text center-align">An ERROR occured, Please Try Again Later!</p>';	
		}else{
			if($class->pass_verify($_POST['passphrase'],$_SESSION['aj.ldc']['ldcid'])){
				$_SESSION['aj.ldc']['course'] = $_POST['course'];
				$url = __url__.'/ldc/attendance/';
				echo 
				"<script>
					$('#gaclModal').modal('close'); 
					window.open('".$url."', '_blank','fullscreen=yes, channelmode=1') 
				</script>";
			}else{
				echo '<p class="red-text center-align">An ERROR occured, Please Check Password and Try Again!</p>';	
			}
		}
	}	


// RECORDING ATTENDANCE
	if (isset($_POST['registry'])) {
		$result = $class->registry($_POST['username'],$_POST['passphrase'],$_POST['registry']);
		if ($result === false) {
			echo '<p class="red-text center">An Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Successfully recorded as Present!</p>';
		}
	}


// EDIT USER
	if (isset($_POST['edituser'])) {
		if (!isset($_POST['password']) || empty($_POST['password'])) {
			$result = $class->edituser($_POST['name'],$_POST['position'],$_POST['email'],$_POST['phone'],$_POST['edituser']);

			if ($result === true)
				echo '<p class="center-align green-text flow-text">User Information updated Successfully</p>';
			else
				echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
		}elseif ($_POST['password'] !== $_POST['cpassword']){
			echo '<p class="center-align red-text flow-text">Passwords don\'t match!"</p>';	
		} else {
			$result = $class->resetuser($_POST['name'],$_POST['position'],$_POST['email'],$_POST['phone'],$_POST['password'],$_POST['edituser']);

			if ($result === true)
				echo '<p class="center-align green-text flow-text">User Information was reset Successfully</p>';
			else
				echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
		}
	}


// ADD USER
	if (isset($_POST['adduser'])) {		
		if ($_POST['password'] !== $_POST['cpassword']){
			echo '<p class="center-align red-text flow-text">Passwords don\'t match!"</p>';	
		}else{		
		$result = $class->adduser($_POST['name'],$_POST['position'],$_POST['email'],$_POST['phone'],$_POST['password'],$_SESSION['aj.ldc']['ldcid']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">User added Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';	
		}	
	}


// DELETE USER
	if (isset($_POST['query']) && $_POST['query'] == 'userdelete') {
		if ($_SESSION['aj.ldc']['ldcpos'] == 'member') {
			echo '<script>Materialize.toast("Please You Don\'t have Such Privilege!", 5000)</script>';
		} else { 
			$result = $class->userdelete($_POST['id'],$_SESSION['aj.ldc']['ldcid']);

			if ($result === false) {
				echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
			}else{
				echo '<script>Materialize.toast("User Successfully Deleted!", 4000)</script>';
			}
		}	
	}	


// EDIT ROOM
	if (isset($_POST['editroom'])) {
		$result = $class->roomupdate($_POST['title'],$_POST['venue'],$_POST['occupancy'],$_POST['editroom']);
		if ($result === false) {
			echo '<p class="red-text center">Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Training Venue updated successfully</p>';
		}
	}


// ADD ROOM
	if (isset($_POST['addroom'])) {
		$result = $class->roomadd($_POST['title'],$_POST['venue'],$_POST['occupancy']);
		if ($result === false) {
			echo '<p class="red-text center">An Error Occured. Please Try Again!</p>';	
		}else{
			echo '<p class="green-text center flow-text">Training Venue added successfully</p>';
		}
	}


// DELETE ROOM
	if (isset($_POST['query']) && $_POST['query'] === 'roomdelete') {
		$result = $class->roomdel($_POST['id']);
		if ($result === false) {
			echo '<script>Materialize.toast("An Error Occured. Please Try Again!", 2000)</script>';	
		}else{
			echo '<script>Materialize.toast("Venue deleted successfully!", 2000)</script>';
		}
	}
	

// RUP SEARCH
	if (isset($_POST['rupsearch'])) {
		$persons = $class->rupsearch($_POST['rupsubject']);
		if ($persons !== false) { ?>
			<table>
				<thead>
					<th class="center">PARTICIPANT(S) FOUND</th>
				</thead>
				<tbody>
				<?php foreach($persons as $person) { ?>
					<tr class="cursor spec-ajax" data-query="rupperson" id="<?= $person['id'] ?>" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" data-toggle="modal">
						<td><?= $person['name'].' - '.$person['staff_id'] ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php }else{
			echo '<p class="brown-text center flow-text">No Participant(s) Found</p>';
		}
	}


// RUP PERSON
	if (isset($_POST['query']) && $_POST['query'] === 'rupperson') { ?>
        <div class="rup_form">
          <p class="flow-text">PASSWORD RESET</p>
            <div class="col s12">
              <form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" form-type="form">
                <div class="input-field">
                  <input type="password" id="npass" name="npass" required="true">
                  <label for="password">New Password</label>
                </div>
                <div class="input-field">
                  <input type="password" id="rpass" name="rpass" required="true">
                  <label for="password">Retype Password</label>
                </div>
                <div class="input-field center-align">
                  <input type="hidden" name="rupreset" value="<?= $_POST['id'] ?>">
                  <input type="submit" value="CONFIRM" class="btn green darken-2">
                </div>
              </form>
            </div>
        </div>
	<?php }


// RUP RESET
	if (isset($_POST['rupreset'])) {
		if ($_POST['npass'] != $_POST['rpass']) {
			echo '<p class="center-align flow-text">Passwords don\'t match</p>';
			return ;
		}

		$result = $class->rupreset($_POST['npass'],$_POST['rupreset']);

		if ($result === true)
			echo '<p class="center-align flow-text green-text">Password reseted Successfully</p>';
		else
			echo '<p class="center-align flow-text red-text">Please check your entries!</p>';
	}		

?>
