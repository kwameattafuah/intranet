<?php
	// include definition
	include("../../../layout/definition.php");

	include("../../../controllers/ldc.controller.php");

	// include head
	include("../../../layout/head.php");

	 // initialise controller class
	 $class = new Ldc; 

	list($null,$depts) = $class->course_reg();

?> 
   
<div class="row">
	<div class="col s12 m10 offset-m1">
		<div class="card z-depth-3 white">
	<?php if(isset($_GET['appraisal'])) { ?>
		<div class="col s12 m6 offset-m3 card">
		  <nav class="green darken-3">
		    <div class="nav-wrapper" style="padding-left: 3%; padding-right: 5%;">
		      <h5 class="center" style="padding-top: 3%">
				<a href="#">GACL LDC PARTICIPANT APPRAISAL</a>
		      </h5>	
		    </div>
		  </nav>			
			<div class="card-content" id="display">
				<form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" data-output="#display" form-type="form">
					<div class="input-field">
						<input type="text" name="fullname" id="fullname" required="true">
						<label for="fullname">Full Name</label>
					</div>
					<div class="input-field">
						<input type="email" name="email" id="email" required="true">
						<label for="email">Email Address</label>
					</div>
					<div class="input-field">
						<input type="text" name="appraisalkey" id="appraisalkey" required="true">
						<label for="appraisalkey">Appraisal Key</label>
					</div>
					<div class="input-field">
					    <select required="true" class="browser-default dynamic-dual" dual-data="#appraisalkey" name="appraisaldept" data-output="#validatearea" data-query="appraisaldept" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>">
					      <option value="" disabled selected>select your Department</option>
					    <?php 
							if ($depts !== false)
								foreach ($depts as $dept) {
						?> 
					      <option value="<?= $dept['dept_id']?>"><?= $dept['name'] ?></option>
					      <?php } ?>
					    </select>
					<div class="input-field center-align" id="validatearea">
					</div>    
			        <div class="input-field center-align">
						<input type="hidden" name="appraisalvalidate" value="set">
						<input type="submit" class="btn green darken-2" value="VALIDATE">
					</div>
				</form>	
			</div>	
		</div>			
	<?php //evaluation forms
		} elseif (isset($_GET['course_evaluation'])) { 
			$thecourse = $_GET['aj'] - 16;
			$thecourse /= 2014;
			$sections = $class->evaluations_sections(); 
	?>

		  <nav class="blue darken-4">
		    <div class="nav-wrapper" style="padding-left: 3%; padding-right: 5%;">
		      <p>
		      	<span style="font-size: 140%"><a href="#">GACL LDC COURSE EVALUATION FORM</a></span>
		      	<span class="right">Welcome, <?php echo $_SESSION['aj.ldc']['ldcuser'] ?></span>
		      </p>	
		    </div>
		  </nav>	

			<div class="card-content" id="display">
				<form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" data-output="#display" form-type="form">	
					<p class="grey-text"> We appreciate your help in evaluating this training programme. Please select the rating for each section based on Rating Scale </p>	
					<table class="table-responsive striped">
					<?php $j=0; 
						foreach ($sections as $section) { 
						$questions = $class->evaluations_questions($section['id']);
					?>
						<thead>
							<th class="center"><?= $section['caption'] ?></th>
							<th class="red-text text-lighten-1">STRONGLY DISAGREE</th>
							<th class="red-text text-darken-3">DISAGREE</th>
							<th>NEUTRAL</th>
							<th class="green-text text-darken-2">AGREE</th>
							<th class="green-text text-lighten-1">STRONGLY AGREE</th>
						</thead>
						<tbody>
						<!-- Question Template -->		
						<?php foreach ($questions as $question) { ++$j; ?>				
							<tr>
								<td> 
									<p> <?= $j.'. '.$question['reads_as'] ?> </p> 
								</td>
								<td class="center">
								    <span>
								      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="grade_E" id="<?= $question['id'].'a' ?>" required>
								      <label for="<?= $question['id'].'a' ?>"></label>
								    </span>
								</td>
								<td class="center">
								    <span class="center">
								      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="grade_D" id="<?= $question['id'].'b' ?>" required>
								      <label for="<?= $question['id'].'b' ?>"></label>
								    </span>
								</td>
								<td class="center">
								    <span class="center">
								      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="grade_C" id="<?= $question['id'].'c' ?>" required>
								      <label for="<?= $question['id'].'c' ?>"></label>
								    </span>
								</td>
								<td class="center">
								    <span class="center">
								      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="grade_B" id="<?= $question['id'].'d' ?>" required>
								      <label for="<?= $question['id'].'d' ?>"></label>
								    </span>
								</td>
								<td class="center">
								    <span class="center">
								      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="grade_A" id="<?= $question['id'].'e' ?>" required>
								      <label for="<?= $question['id'].'e' ?>"></label>
								    </span>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					<?php } $questions = $class->evaluations_comments(); ?>	
					</table>
					<br><h5>Suggestions and Comments</h5><br>

					<?php foreach ($questions as $question) { ++$j; ?>	
						<div class="input-field">
							<span><?= $j.'. '.$question['reads_as'] ?></span>
        					<textarea name="<?= 'comments-'.$question['id'] ?>" id="<?= $question['id'].'comments' ?>" required class="materialize-textarea" minlength="20"></textarea>
        				</div> 
					<?php } ?>
					<div class="input-field" style="padding-bottom: 5%">
						<input type="hidden" name="courseevaluation" value="<?= $thecourse ?>">
						<input type="submit" class="btn green right large" value="SUBMIT" style="width: 20%">
					</div>
				</form>
			</div>	
	<?php } elseif (isset($_GET['appraisalconfirmed'])) { 
		if (isset($_SESSION['aj.ldcappraisal']) && $_SESSION['aj.ldcappraisal'] === true) {
			unset($_SESSION['aj.ldcappraisal']);
			header("Location: ?appraisal");
		} else {	
			$sections = $class->appraisal_sections(); 
		}	
	?>

	  <nav class="green darken-2">
	    <div class="nav-wrapper" style="padding-left: 3%; padding-right: 5%;">
	      <p>
	      	<span style="font-size: 140%"><a href="#">GACL LDC PERFORMANCE APPRAISAL FORM</a></span>
	      	<span class="right">Welcome, <?= ucwords(strtolower($_SESSION['aj.ldcappraisal']['name'])) ?></span>
	      </p>	
	    </div>
	  </nav>	

		<div class="card-content" id="display">
			<form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" data-output="#display" form-type="form">
				<span class="flow-text blue-text text-darken-2">Appraising: </span><span><?= $_SESSION['aj.ldcappraisal']['person'] ?></span>	
				<p class="grey-text"> <br>We appreciate your help in evaluating this course participant. Please select the rating for each section based on Rating Scale </p>	
				<table class="table-responsive striped">
				<?php $j=0; 
					foreach ($sections as $section) { 
					$questions = $class->appraisal_questions($section['id']);
				?>
					<thead>
						<th class="center"><?= $section['caption'] ?></th>
						<th class="red-text text-lighten-1">STRONGLY DISAGREE</th>
						<th class="red-text text-darken-2">DISAGREE</th>
						<th>NEUTRAL</th>
						<th class="green-text text-darken-3">AGREE</th>
						<th class="green-text text-lighten-1">STRONGLY AGREE</th>
					</thead>
					<tbody>
					<!-- Question Template -->		
					<?php foreach ($questions as $question) { ++$j; ?>				
						<tr>
							<td> 
								<p> <?= $j.'. '.$question['reads_as'] ?> </p> 
							</td>
							<td class="center">
							    <span>
							      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="E" id="<?= $question['id'].'a' ?>" required>
							      <label for="<?= $question['id'].'a' ?>"></label>
							    </span>
							</td>
							<td class="center">
							    <span class="center">
							      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="D" id="<?= $question['id'].'b' ?>" required>
							      <label for="<?= $question['id'].'b' ?>"></label>
							    </span>
							</td>
							<td class="center">
							    <span class="center">
							      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="C" id="<?= $question['id'].'c' ?>" required>
							      <label for="<?= $question['id'].'c' ?>"></label>
							    </span>
							</td>
							<td class="center">
							    <span class="center">
							      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="B" id="<?= $question['id'].'d' ?>" required>
							      <label for="<?= $question['id'].'d' ?>"></label>
							    </span>
							</td>
							<td class="center">
							    <span class="center">
							      <input name="<?= $section['id'].'-'.$question['id'] ?>" type="radio" class="with-gap" value="A" id="<?= $question['id'].'e' ?>" required>
							      <label for="<?= $question['id'].'e' ?>"></label>
							    </span>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				<?php } $questions = $class->appraisal_comments(); ?>	
				</table>
				<br><h5>Suggestions and Comments</h5><br>

				<?php foreach ($questions as $question) { ++$j; ?>	
					<div class="input-field">
						<span><?= $j.'. '.$question['reads_as'] ?></span>
    					<textarea name="<?= 'remarks-'.$question['id'] ?>" id="<?= $question['id'].'remarks' ?>" required class="materialize-textarea" minlength="20"></textarea>
    				</div> 
				<?php } ?>
				<div class="input-field" style="padding-bottom: 5%">
					<input type="hidden" name="participantappraisal" value="<?= $_SESSION['aj.ldcappraisal']['appraisee'] ?>">
					<input type="submit" class="btn green right large" value="SUBMIT" style="width: 20%">
				</div>
			</form>
		</div>	
	<?php } elseif (isset($_GET['performance_appraisal'])) { 
				$thecourse = $_GET['aj'] - 61;
				$thecourse /= 1420;
				$sections = $class->appraisal_sections(); 
	?>
	  <nav class="green darken-2">
	    <div class="nav-wrapper" style="padding-left: 3%; padding-right: 5%;">
	      <p>
	      	<span style="font-size: 140%"><a href="#"><?= strtoupper($_GET['performance_appraisal']) ?></a></span>
	      	<span class="right">Welcome, <?= ucwords(strtolower($_SESSION['aj.ldc']['ldcuser'])) ?></span>
	      </p>	
	    </div>
	  </nav>	

		<div class="card-content" id="display">
			<form class="form">	
				<p class="grey-text"> <br>Please provide any remarks if you have concerns about your appraisal.</p>	
				<table class="table-responsive striped">
				<?php $j=0; 
					foreach ($sections as $section) { 
					$questions = $class->participant_questions($thecourse,$section['id'],$_SESSION['aj.ldc']['ldcmail']);
				?>
					<thead>
						<th class="center flow-text" colspan="2"><?= $section['caption'] ?></th>
					</thead>	
					<tbody>
					<!-- Question Template -->		
					<?php foreach ($questions as $question) { 
						++$j;
						switch ($question['grade']) {
							case 'A':
								$score = '<span class="green-text">STRONGLY AGREE</span>';
								break;
							case 'B':
								$score = '<span class="green-text text-darken-2">AGREE</span>';
								break;
							case 'D':
								$score = '<span class="red-text text-darken-2">DISAGREE</span>';
								break;
							case 'E':
								$score = '<span class="red-text">STRONGLY DISAGREE</span>';
								break;		
							default:
								$score = '<span>NEUTRAL</span>';
								break;
						} 
					?>				
						<tr>
							<td> 
								<p> <?= $j.'. '.$question['reads_as'] ?> </p> 
							</td>
							<td class="center">
							    <?= $score ?>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				<?php } $questions = $class->participant_comments($thecourse,$_SESSION['aj.ldc']['ldcmail']); ?>	
				</table>
			</form>	
				<br><h5>Suggestions and Comments</h5><br>

				<?php foreach ($questions as $question) { ++$j; ?>	
					<div class="input-field">
						<p class="flow-text brown-text"><?= $j.'. '.$question['reads_as'] ?></p>
    					<p style="padding-left: 3%"><?= $question['remarks'] ?></p>
    					<br>
    				</div> 
				<?php } 
					list($remarksmade,$show) = $class->fetch_feed($thecourse,$_SESSION['aj.ldc']['ldcmail']);

					if ($remarksmade !== false) { 
						echo '<div class="row"> <br><h5>Remarks Made</h5><br>'; 
						foreach ($remarksmade as $remarkmade) { ?>
						<p class="wrap"><?= ucfirst($remarkmade['details']) ?><br><small class="grey-text"><?= date("jS M. 'y",strtotime($remarkmade['when_was'])) ?></small></p><br>
				<?php	} echo '</div>'; } ?>
					
				<div style="padding-bottom: 3%" class="row action <?= ($show['remarked'] >= 3)? 'hide' : '' ?>">
					<button class="btn green right large spec-ajax" data-extend-view=".remark_form" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true">Add Remark(s)</button>
					<div class="remark_form hide">
			          <div class="row">
			            <div class="col s12">
			            	<h5>COURSE APPRAISAL REMARKS FORM</h5>	
			              <form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" form-type="form">
			                <div class="input-field">
			                  <textarea name="details" required class="materialize-textarea" minlength="20" placeholder="Please type any remarks about your appraisal here."></textarea>
			                </div>
			                <div class="input-field center-align">
			                  <input type="hidden" name="appraisalremark" value="<?= $thecourse ?>">
			                  <input type="submit" value="SEND REMARKS" class="btn green darken-2">
			                </div>
			              </form>
			            </div>
			          </div>  
			        </div>
				</div>	
		</div>					
	<?php } ?>
			<div class="card-action">
				<p class="center">Powered by <a href="" class="blue-text text-darken-2">GACL ICT <span class="green-text text-darken-1">&copy <?= $copyright ?> </span></a></p>	
			</div>			
		</div>		
	</div>
</div>

<?php 
	// include foot
	include("../../../layout/foot.php");
?>