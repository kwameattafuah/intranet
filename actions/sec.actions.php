<?php  
	// include define
	include("../layout/definition.php");
	// include controller class
	include("../controllers/sec.controller.php");
	// initialise controller class
	$class = new Sec;

	$depts = $class->generate(); 

// NEW DOCUMENT CREATION FOR INTRA OR INTER DEPARTMENT DISPATCH
	if (isset($_POST['int-generate'])) {
		$dept = isset($_POST['dept'])? $_POST['dept'] : $_SESSION['aj.gaclintra']['dept'];
		$receiver = trim(stripslashes(strip_tags($_POST['name'])));
		if ($_POST['dept'] === $_SESSION['aj.gaclintra']['dept'] && empty($receiver)) {
			echo "<p class='flow-text red-text center'>Please enter Receiver if document is within your Department</p>";
		}elseif ($_POST['dept'] !== $_SESSION['aj.gaclintra']['dept'] && !empty($receiver)) {
			echo "<p class='flow-text red-text center'>The Receiver cannot be entered if the document is not for your department</p>";
		}elseif ($_POST['dept'] === $_SESSION['aj.gaclintra']['dept'] && $_POST['cat'] != 1) {
			echo "<p class='flow-text red-text center'>You cannot choose your department if the dispatch category is not Internal</p>";
		}elseif ($_POST['dept'] !== $_SESSION['aj.gaclintra']['dept'] && $_POST['cat'] == 1) {
			echo "<p class='flow-text red-text center'>You cannot choose a different department if the dispatch category is Internal</p>";
		}else{
			$response = $class->dispatch($_POST['source'],$_POST['sdate'],$_SESSION['aj.gaclintra']['dept'],$_POST['whom'],$_POST['subject'],$receiver,$dept,$_POST['remarks'],$_POST['cat']);
			if ($response !== false)
				echo "<p class='flow-text green-text center'>Document generated successfully with the code:<br><span class='black-text'><b>".$response."</b></span></p>";
			else
				echo "<p class='flow-text red-text center'>An error occured.<br>Please Try Again!</p>";			
		}	
	}


// NEW DOCUMENT CREATION FOR RECEIPT OF EXTERNAL DOCUMENT
	if (isset($_POST['ext-generate'])) {
		$response = $class->extreceive($_POST['source'],$_POST['sdate'],$_POST['whom'],$_POST['subject'],$_POST['receiver'],$_SESSION['aj.gaclintra']['dept'],$_POST['remarks'],$_POST['ext-generate']);
		if ($response !== false)
			echo "<p class='flow-text green-text center'>Document generated successfully with the code:<br><span class='black-text'><b>".$response."</b></span></p>";
		else
			echo "<p class='flow-text red-text center'>An error occured.<br>Please Try Again!</p>";			
	}


// EDIT NON INTERNAL DOCUMENT
	if (isset($_POST['edit-document'])) {
		$receiver = (!isset($_POST['receiver']))? 'Pending' : $_POST['receiver'];
		$response = $class->extedit($_POST['source'],$_POST['sdate'],$_POST['whom'],$_POST['subject'],$receiver,$_POST['remarks'],$_POST['edit-document']);
		if ($response !== false)
			echo "<p class='flow-text green-text center'>Document details successfully updated.</p>";
		else
			echo "<p class='flow-text red-text center'>An error occured.<br>Please Try Again!</p>";			
	}	


// EDIT INTERNAL DOCUMENT
	if (isset($_POST['edit-internal'])) {
		$response = $class->intedit($_POST['source'],$_POST['sdate'],$_POST['whom'],$_POST['subject'],$_POST['receiver'],$_POST['remarks'],$_POST['state'],$_POST['edit-internal']);
		if ($response !== false)
			echo "<p class='flow-text green-text center'>Successfully updated.</p>";
		else
			echo "<p class='flow-text red-text center'>An error occured.<br>Please Try Again!</p>";			
	}	


// DOCUMENT ASPECTS GENERATION
	if (isset($_POST['id']) && ($_POST['id'] == "cattype" || $_POST['id']== "catnumber")) { 
		$num = ($_POST['id'] == "cattype")? $_POST['bsearch'] : $_POST['asearch'];
		$cat = ($_POST['id'] == "catnumber")? $_POST['bsearch'] : $_POST['asearch'];
		for ($aj = 1; $aj <= $num; $aj++) {
			if ($cat == 2) { 
		?>	
	        <div class="input-field">
			    <select class="browser-default" name="dept">
			      <option value="" disabled selected>select the Department</option>
			    <?php 
					if ($depts !== false)
						foreach ($depts as $dept) {
				?> 
			      <option value="<?= $dept['dept_id']?>"><?= $dept['name'] ?></option>
			      <?php } ?>
			    </select>
	        </div>
			<div class="input-field">
				<input type="text" name="whom" id="whom" required="true">
				<label for="whom">Whom Dispatched (<?= $aj ?>)</label>
			</div>
			<div class="input-field">
				<input type="text" name="remarks" id="remarks" >
				<label for="remarks">Remarks</label>
			</div>	        	
		<?php } else { ?>
			<div class="input-field">
				<input type="text" name="whom" id="whom" required="true">
				<label for="whom">Whom Dispatched (<?= $aj ?>)</label>
			</div>
			<div class="input-field">
				<input type="text" name="remarks" id="remarks" >
				<label for="remarks">Remarks</label>
			</div>				        
			<div class="input-field">
				<input type="text" name="name" id="name" >
				<label for="name">Receiver's Name</label>
			</div>					
		<?php } 
		}
	}


// EXTERNAL DOCUMENT CREATION
	if (isset($_POST['query']) && $_POST['query'] == "ext-generate") { ?>
		<form class="form" data-dest="<?= __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" form-type="form">
			<div class="row">	
				<span class="flow-text"><b>External Document Receipt</b></span>
				<span class="right blue-text text-darken-3"><?= date("jS M, Y") ?></span>
			</div>
			<div class="row">
				<div class="input-field col s12 m7 no-pad-left">
					<input type="text" name="source" id="source" required="true">
					<label for="source">Document Source</label>
				</div>
				<div class="input-field col s12 m5 no-pad-right">
					<input type="date" name="sdate" id="sdate" required="true">
					<label for="sdate"></label>
				</div>
			</div>		
			<div class="input-field">
				<input type="text" name="whom" id="whom" required="true">
				<label for="whom">For Whom Received</label>
			</div>			
			<div class="input-field">
				<input type="text" name="subject" id="subject" required="true">
				<label for="subject">Subject</label>
			</div>	
			<div class="input-field">
				<input type="text" name="receiver" id="receiver" required="true">
				<label for="name">Receiver's Name</label>
			</div>						
			<div class="input-field">
				<input type="text" name="remarks" id="remarks" >
				<label for="remarks">Remarks</label>
			</div>	        																	
			<div class="input-field center-align">
				<input type="hidden" name="ext-generate" value="3">
				<button type="submit" class="waves-effect waves-light btn green darken-3" style="width: 30%">DONE</button>
			</div>
		</form>	

	<?php	}	

// INTERNAL DOCUMENT CREATION
	if (isset($_POST['query']) && $_POST['query'] == "int-generate") { ?>
		<form class="form" data-dest="<?= __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" form-type="form">
			<div class="row">	
				<span class="flow-text"><b>New Document Details</b></span>
				<span class="right blue-text text-darken-3"><?= date("jS M, Y") ?></span>
			</div>
			<div class="row">
				<div class="input-field col s12 m7 no-pad-left">
					<input type="text" name="source" id="source" required="true">
					<label for="source">Document Source</label>
				</div>
				<div class="input-field col s12 m5 no-pad-right">
					<input type="date" name="sdate" id="sdate" required="true">
					<label for="sdate"></label>
				</div>
			</div>
			<div class="input-field">
				<input type="text" name="subject" id="subject" required="true">
				<label for="subject">Subject</label>
			</div>

			<div class="row">
		        <div class="input-field col s12 m9 no-pad-left">
				    <select id="cattype" class="browser-default dynamic-dual" name="cat" required data-query="cattype" data-dest="<?php echo __url__.'/actions/sec.actions.php'; ?>" data-output="#creation_place" dual-data="#catnumber">
				      <option disabled selected>Select the Dispatch Type</option>
				      <option value="2">Inter Department</option>
				      <option value="1">Internal Dispatch</option>
				    </select>
		        </div>
		        <div class="input-field col s12 m3 no-pad-right">
		        	<input type="number" name="catnumber" id="catnumber" required="true" class="dynamic-dual center" dual-data="#cattype" data-query="catnumber" data-dest="<?php echo __url__.'/actions/sec.actions.php'; ?>" data-output="#creation_place">
					<label for="catnumber">Number</label>
		        </div>
		    </div>    

			<div id="creation_place"></div>

			<div class="input-field center-align">
				<input type="hidden" name="int-generate" value="set">
				<button type="submit" class="waves-effect waves-light btn orange darken-3" style="width: 30%">DONE</button>
			</div>
		</form>

<?php }		

// DELETION OF DOCUMENT
	if (isset($_POST['query']) && $_POST['query'] == "delete-document" ) { 
		$result = $class->deldocument($_POST['id']);

		if ($result === false) {
			echo '<h4 class="red-text center">An unexpected error occured!</h4>';
		}else{
			echo '<h4 class="brown-text center">Document Record Deleted!</h4>';
		}
	}


// DOCUMENT(S) DISPLAY INTERFACE
	if (isset($_POST['search']) && $_POST['id'] === 'dispatch-type') {
		// search		
		if ($_POST['search'] == 1) {
			$docs = $class->intranalindex($_SESSION['aj.gaclintra']['dept']);
			// include display
			include("../views/correspondence/intranal.php");
		}elseif ($_POST['search'] == 2)  {
			$docs = $class->internalindex($_SESSION['aj.gaclintra']['dept']);
			include("../views/correspondence/internal.php");
		}else{
			$docs = $class->index($_SESSION['aj.gaclintra']['dept']);
			// include display
			include("../views/correspondence/external.php");
		}	
	}	


// LOGOUT
	if (isset($_POST['query']) && $_POST['query'] == "logout"){
		echo '<script>window.close();</script>';
	}


// DOCUMENT SEARCH
	if (isset($_POST['docsearch'])) {
		$item = trim($_POST['docsubject']);
		$criteria = $_POST['doccriteria'];
		$source = (isset($_POST['docsource']))? trim($_POST['docsource']) : null;
		$department = (isset($_POST['docdept']))? $_POST['docdept'] : null;

		if (is_null($source) && is_null($department)) {
			$docs = $class->itemcrit($item,$criteria);
		} elseif (!is_null($source) && is_null($department)) {
			$docs = $class->itemcritsource($item,$criteria,$source);
		} elseif (is_null($source) && !is_null($department)) {
			$docs = $class->itemcritdept($item,$criteria,$department);
		}

		if ($docs !== false) { 
     	 echo '<h5 class="center">Documnet Search Results</h5>'; 
     	 foreach($docs as $doc) { //display of results ?>
            <div class="card">
                <div class="card-content" style="padding: 5px 10px 5px 10px">
                	<p class="cursor spec-ajax" data-query="externaldoc" data-value="<?= $doc['id'] ?>" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-toggle="modal" id="<?= 'document'.$doc['id'] ?>">
                		<?= $doc['subject'] .' - '. date('jS M, Y', strtotime($doc['disdate'])); ?> 
                	</p>
                </div>	
            </div>
        <?php   } }else{ ?>
          	<h4 class="center red-text text-lighten-3">NO Documents found!</h4>
      <?php } }


// INTERNAL DOCUMENT VIEW
	if (isset($_POST['query']) && $_POST['query'] == "dispatchdoc") { 
		$info = $class->fetch($_POST['value']); ?>	
		<div class="row">
			<div class="card-title center">
				<h5 class="bold"><?= strtoupper($info['subject']) ?></h5>
				<p class="flow-text bold green-text text-darken-3"><?= $info['code'] ?></p>
			</div>
			<div class="divider"></div>
			<div class="card-content">
				<div class="row">
					<div class="col s12 m6">
						<h5 class="blue-text text-darken-2">Document Source</h5><span><?= $info['source'] ?></span>
						<h5 class="blue-text text-darken-2">Document Date</h5><span><?= date('jS M, Y',strtotime($info['sdate'])) ?></span>
					</div>
					<div class="col s12 m6">
						<h5 class="blue-text text-darken-2">To Whom Dispatched</h5><span><?= $info['to_whom'] ?></span>
						<h5 class="blue-text text-darken-2">Department To</h5><span><?= $info['recepient'] ?></span>
					</div>
				</div>									
				<div class="col s12 m6">
					<h5 class="blue-text text-darken-2">Remarks</h5><span><?= empty($info['remarks'])? 'n/a' : $info['remarks'] ?></span>
				</div>
				<div class="col s12 m6">
					<h5 class="blue-text text-darken-2">Received</h5><span><?= ($info['receiver'] != 'Pending')? $info['receiver'].' | '.date('jS M, Y', strtotime($info['recdate'])) : '<span class="red-text">Pending' ?></span>
				</div>
			</div>

			  <div class="action">
			      <div class="fixed-action-btn horizontal <?= ($info['state'] == 0 && $info['sendept'] === $_SESSION['aj.gaclintra']['dept'])? '' : 'hide' ?>">
			        <a class="btn-floating btn-large red darken-2 waves-effect ">
			          <i class="material-icons">menu</i>
			        </a>
			        <ul>                     
			          <li>
			            <a href="" class="btn-floating yellow darken-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Edit" data-extend-view="#edit-document" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
			              <i class="material-icons">edit</i>
			            </a>
			          </li>
			          <li>
			            <a href="" class="btn-floating blue lighten-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Delete" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-query="delete-document" data-fadeOut="document<?= $info['id'] ?>" id="<?= $info['id'] ?>">
			              <i class="material-icons">delete</i>
			            </a>
			          </li>                   
			        </ul>
			      </div>

					<div id="edit-document" class="hide">
						<form class="form" data-dest="<?= __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" form-type="form">
							<h4>Document Details</h4>
							<div class="row">
								<div class="input-field col s12 m7 no-pad-left">
									<input type="text" name="source" id="source" required="true" value="<?= $info['source'] ?>">
									<label for="source">Document Source</label>
								</div>
								<div class="input-field col s12 m5 no-pad-right">
									<input type="date" name="sdate" id="sdate" required="true" value="<?= $info['sdate'] ?>">
									<label for="sdate"></label>
								</div>
							</div>		
							<div class="input-field">
								<input type="text" name="whom" id="whom" required="true" value="<?= $info['to_whom'] ?>">
								<label for="whom">To Whom Dispatched</label>
							</div>			
							<div class="input-field">
								<input type="text" name="subject" id="subject" required="true" value="<?= $info['subject'] ?>">
								<label for="subject">Subject</label>
							</div>	
							<div class="input-field">
								<input type="text" name="receiver" id="receiver" required="true" value="<?= $info['receiver'] ?>" <?= ($info['receiver'] == 'Pending')? 'disabled' : '' ?> >
								<label for="name">Receiver's Name</label>
							</div>						
							<div class="input-field">
								<input type="text" name="remarks" id="remarks" value="<?= $info['remarks'] ?>">
								<label for="remarks">Remarks</label>
							</div>	        																	
							<div class="input-field center-align">
								<input type="hidden" name="edit-document" value="<?= $info['id'] ?>">
								<button type="submit" class="waves-effect waves-light btn blue lighten-2" style="width: 30%">SAVE</button>
							</div>
						</form>						
					</div>			      
			   </div>				
			</div>	

	<?php	} 

// EXTERNAL DOCUMENT VIEW	
	if (isset($_POST['query']) && $_POST['query'] == "externaldoc") { 
		$info = $class->fetch($_POST['value']); ?>	
		<div class="row">
			<div class="card-title center">
				<h5 class="bold"><?= strtoupper($info['subject']) ?></h5>
				<p class="flow-text bold green-text text-darken-3"><?= $info['code'] ?></p>
			</div>
			<div class="divider"></div>
			<div class="card-content">
				<div class="row">
					<div class="col s12 m6">
						<h5 class="blue-text text-darken-2">Document Source</h5><span><?= $info['source'] ?></span>
						<h5 class="blue-text text-darken-2">Document Date</h5><span><?= date('jS M, Y',strtotime($info['sdate'])) ?></span>
					</div>
					<div class="col s12 m6">
						<h5 class="blue-text text-darken-2">For Whom Received</h5><span><?= $info['to_whom'] ?></span>
						<h5 class="blue-text text-darken-2">Status</h5><span>
							<?php if ($info['status'] == 1 || $info['status'] == 4) { echo 'In Department'; } elseif ($info['status'] == 3) { echo 'Filed'; } else { echo 'Dispatched'; } ?></span>
					</div>
					<div class="col s12 m6">
						<h5 class="blue-text text-darken-2">Remarks</h5><span><?= empty($info['remarks'])? 'n/a' : $info['remarks'] ?></span>
					</div>
					<div class="col s12 m6">
						<h5 class="blue-text text-darken-2">Received</h5><span><?= ($info['receiver'] != 'Pending')? $info['receiver'].' | '.date('jS M, Y', strtotime($info['recdate'])) : '<span class="red-text">Pending' ?></span>
					</div>	
				</div>									
			</div>
			  <div class="action">
				<div class="fixed-action-btn horizontal <?= ($info['status'] == 2 && $info['state'] == 0)? '' : 'hide' ?>">
					<a href="" class="btn-floating brown waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Receive" data-extend-view="#receive_form" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
			          <i class="material-icons">archive</i>
			        </a>
				</div>

				<div class="fixed-action-btn horizontal <?= ($info['status'] != 2)? '' : 'hide' ?>">
					<a href="" class="btn-floating green waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Dispatch" data-extend-view="#dispatch-document" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
			            <i class="material-icons">forward</i>
			        </a>
				</div>

			      <div class="fixed-action-btn horizontal <?= ($info['status'] == 1 && is_null($info['sendept']))? '' : 'hide' ?>">
			        <a class="btn-floating btn-large red darken-2 waves-effect ">
			          <i class="material-icons">menu</i>
			        </a>
			        <ul>
			          <li>
			            <a href="" class="btn-floating green waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Dispatch" data-extend-view="#dispatch-document" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
			              <i class="material-icons">forward</i>
			            </a>
			          </li>                     
			          <li>
			            <a href="" class="<?= ($info['status'] == 1 || $info['status'] == 3)? '' : 'hide' ?>btn-floating yellow darken-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Edit" data-extend-view="#edit-document" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
			              <i class="material-icons">edit</i>
			            </a>
			          </li>
			          <li>
			            <a href="" class="btn-floating blue lighten-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Delete" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-query="delete-document" data-fadeOut="document<?= $info['id'] ?>" id="<?= $info['id'] ?>">
			              <i class="material-icons">delete</i>
			            </a>
			          </li>                   
			        </ul>
			      </div>

			      <div id="receive_form" class="hide">
			      	<form class="form" data-dest="<?= __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" form-type="form">
			      		<h4>Receipt Form</h4>
			      		<div class="input-field">
							<input type="text" name="receivedby" id="receivedby" required="true">
							<label for="receivedby">Please enter your name here</label>
						</div>
						<div class="input-field center-align">
							<input type="hidden" name="receive-document" value="<?= $info['id'] ?>">
							<button type="submit" class="waves-effect waves-light btn blue darken-1" style="width: 50%">RECEIVE</button>
						</div>
			      	</form>
			      </div>

				<!-- DISPATCH FORM -->
					<div id="dispatch-document" class="hide">
						<form class="form" data-dest="<?= __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" form-type="form">
							<h4>Dispatch Form</h4>
							<div class="row">
								<input id="patchnum" type="search" required name="patchnum" placeholder="How many recepients for this dispatch" class="col s7 center dynamic-search" data-query="patchnum" data-dest="<?php echo __url__.'/actions/sec.actions.php'; ?>" data-output="#dispatch_form" data-id="<?= $info['id'] ?>">
								<input type="submit" value="SEND" class="right col s4 btn green darken-2">
							</div>			
							<div id="dispatch_form"> 
					        </div>
				    	</form>
					</div>
				<!--/ DISPATCH FORM -->	

					<div id="edit-document" class="hide">
						<form class="form" data-dest="<?= __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" form-type="form">
							<h4>Document Details</h4>
							<div class="row">
								<div class="input-field col s12 m7 no-pad-left">
									<input type="text" name="source" id="source" required="true" value="<?= $info['source'] ?>">
									<label for="source">Document Source</label>
								</div>
								<div class="input-field col s12 m5 no-pad-right">
									<input type="date" name="sdate" id="sdate" required="true" value="<?= $info['sdate'] ?>">
									<label for="sdate"></label>
								</div>
							</div>		
							<div class="input-field">
								<input type="text" name="whom" id="whom" required="true" value="<?= $info['to_whom'] ?>">
								<label for="whom">For Whom Received</label>
							</div>			
							<div class="input-field">
								<input type="text" name="subject" id="subject" required="true" value="<?= $info['subject'] ?>">
								<label for="subject">Subject</label>
							</div>	
							<div class="input-field">
								<input type="text" name="receiver" id="receiver" required="true" value="<?= $info['receiver'] ?>">
								<label for="name">Receiver's Name</label>
							</div>						
							<div class="input-field">
								<input type="text" name="remarks" id="remarks" value="<?= $info['remarks'] ?>">
								<label for="remarks">Remarks</label>
							</div>	        																	
							<div class="input-field center-align">
								<input type="hidden" name="edit-document" value="<?= $info['id'] ?>">
								<button type="submit" class="waves-effect waves-light btn green darken-2" style="width: 30%">SAVE</button>
							</div>
						</form>						
					</div>			      
			   </div>  			
		</div>

<?php	} 

// INTERNAL DOCUMENT VIEW
	if (isset($_POST['query']) && $_POST['query'] == "intranaldoc") { 
		$info = $class->fetch($_POST['value']); ?>	
		<div class="row">
			<div class="card-title center">
				<h5 class="bold"><?= strtoupper($info['subject']) ?></h5>
				<p class="flow-text bold green-text text-darken-3"><?= $info['code'] ?></p>
			</div>
			<div class="divider"></div>
			<div class="card-content">
				<div class="row">
					<div class="col s12 m6">
						<h5 class="blue-text text-darken-2">Document Source</h5><span><?= $info['source'] ?></span>
						<h5 class="blue-text text-darken-2">Document Date</h5><span><?= date('jS M, Y',strtotime($info['sdate'])) ?></span>
						<h5 class="blue-text text-darken-2">Given Out</h5><span><?= date('jS M, Y',strtotime($info['recdate'])) ?></span>
						<h5 class="blue-text text-darken-2">Status</h5>
							<?php if ($info['status'] == 1) { echo '<span class="green-text">Completed'; } else { echo '<span class="red-text">Incomplete'; } ?></span>
					</div>
					<div class="col s12 m6">
						<h5 class="blue-text text-darken-2">From Whom</h5><span><?= $info['to_whom'] ?></span>
						<h5 class="blue-text text-darken-2">Minuted To</h5><span><?= $info['receiver'] ?></span>
						<h5 class="blue-text text-darken-2">Actions</h5><span><?= $info['remarks'] ?></span>
					</div>	
				</div>									
			</div>
			  <div class="action">
			      <div class="fixed-action-btn horizontal <?= ($info['category'] == 1)? '' : 'hide' ?>">
			        <a class="btn-floating btn-large red darken-2 waves-effect ">
			          <i class="material-icons">menu</i>
			        </a>
			        <ul>                     
			          <li>
			            <a href="" class="btn-floating yellow darken-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Edit" data-extend-view="#editint-document" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
			              <i class="material-icons">edit</i>
			            </a>
			          </li>
			          <li>
			            <a href="" class="btn-floating blue lighten-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Delete" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-query="delete-document" data-fadeOut="document<?= $info['id'] ?>" id="<?= $info['id'] ?>">
			              <i class="material-icons">delete</i>
			            </a>
			          </li>                   
			        </ul>
			      </div>

				<div id="editint-document" class="hide">
					<form class="form" data-dest="<?= __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" form-type="form">
						<h4>Document Details</h4>
						<div class="row">
							<div class="input-field col s12 m7 no-pad-left">
								<input type="text" name="source" id="source" required="true" value="<?= $info['source'] ?>">
								<label for="source">Document Source</label>
							</div>
							<div class="input-field col s12 m5 no-pad-right">
								<input type="date" name="sdate" id="sdate" required="true" value="<?= $info['sdate'] ?>">
								<label for="sdate"></label>
							</div>
						</div>		
						<div class="input-field">
							<input type="text" name="whom" id="whom" required="true" value="<?= $info['to_whom'] ?>">
							<label for="whom">From Whom</label>
						</div>			
						<div class="input-field">
							<input type="text" name="subject" id="subject" required="true" value="<?= $info['subject'] ?>">
							<label for="subject">Subject Matter</label>
						</div>	
						<div class="input-field">
							<input type="text" name="receiver" id="receiver" required="true" value="<?= $info['receiver'] ?>">
							<label for="name">Minuted To</label>
						</div>						
						<div class="input-field">
							<input type="text" name="remarks" id="remarks" required="true" value="<?= $info['remarks'] ?>">
							<label for="remarks">Actions</label>
						</div>
						<div class="input-field">
							<select class="browser-default" name="state" required>
						      <option value="" disabled>Select State</option>
						      <option value="0" <?= ($info['state'] == 0)? 'selected':'' ?> >Not Completed</option>
								<option value="1" <?= ($info['state'] == 1)? 'selected':'' ?> >Completed</option>
						    </select>
						</div>	        																	
						<div class="input-field center-align">
							<input type="hidden" name="edit-internal" value="<?= $info['id'] ?>">
							<button type="submit" class="waves-effect waves-light btn green darken-2" style="width: 30%">SAVE</button>
						</div>
					</form>						
				</div>	

			</div>  			
		</div>

<?php }

// DISPATCH FORM GENERATION
	if (isset($_POST['search']) && $_POST['id']== "patchnum") { 
		$count = intval(trim($_POST['search']));
		for ($i = 1; $i <= $count; $i++) { 
	?>	
        <div class="row">
            <div class="input-field col 12 m6">
				<input type="text" name="whom<?= $i ?>" id="whom<?= $i ?>" class="center" required="true">
				<label for="whom">To Whom (<?= $i ?>)</label>
			</div>	
	        <div class="input-field col 12 m6">
			    <select class="browser-default" name="dept<?= $i ?>" required>
			      <option value="" disabled selected>select the Department</option>
			    <?php 
					if ($depts !== false)
						foreach ($depts as $dept) {
				?> 
			      <option value="<?= $dept['dept_id']?>"><?= $dept['name'] ?></option>
			      <?php } ?>
			    </select>
	        </div>
	    </div>    
		<div class="input-field">
			<input type="text" class="col s12" name="remarks<?= $i ?>" id="remarks<?= $i ?>" >
			<label for="remarks">Remarks</label>
		</div>	
	<?php } ?>		        
           <input type="hidden" name="send" value="<?= $_POST['table'] ?>">
						
	<?php } 

// DOCUMENT SEARCH MODULE
	if (isset($_POST['search']) && $_POST['id']== "doccriteria") { 
		if ($_POST['search'] == 3) { 
	?>	
		<input type="text" name="docsource" id="docsource" class="center">
		<label for="docsource">Name of Source / Code</label>

	<?php }elseif ($_POST['search'] == 2) {?>		
	    <select class="browser-default" name="docdept" required>
	      <option value="" disabled>Select the Department</option>
	      <option value="all" selected>All Departments</option>
	    <?php 
			if ($depts !== false)
				foreach ($depts as $dept) {
		?> 
	      <option value="<?= $dept['dept_id']?>"><?= $dept['name'] ?></option>
	      <?php } ?>
	    </select>
<?php 	} 
	}	


// ACKNOWLEDGE DOCUMENT RECEIPT
	if (isset($_POST['receive-document'])) {
		if ($class->receive($_POST['receive-document'],$_POST['receivedby'])) {
			echo '<p class="center-align flow-text green-text">Receipt of Document Acknowledged!</p>';
		}else{
			echo '<p class="center-align flow-text red-text">Error! Please Try Again</p>';
		}
	}


// SEND DISPATCHED DOCUMENT(S)
	if (isset($_POST['send'])) {
		$count = intval(trim($_POST['patchnum']));
		for ($i = 1; $i <= $count; $i++) {  
			$response = $class->send($_POST['send'],$_POST['whom'.$i],$_POST['dept'.$i],$_POST['remarks'.$i]);
			if ($i == $count && $response !== false) {
				echo '<p class="center-align flow-text green-text">Document processed for Dispatch!</p>';
			}elseif ($i == $count && $response === false) {
				echo '<p class="center-align flow-text red-text">Error! Please Try Again</p>';
			}
		}	
	}
?>

