<?php  
	// initialise controller class
	$class = new Forum;

	// get default
	list($recents,$mine) = $class->index($_SESSION['aj.gaclintra']['id']);
?>

<main>
	<div class="row" style="padding-top: 10px">
		<div class="row" style="margin-bottom: 0px">
			<div class="col s12 m6 action" style="padding-top: 27px">
				<div class="wow fadeInLeft">
					<button class="btn white spec-ajax yellow-text text-darken-3" data-extend-view=".add" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true"><i class="material-icons left">input</i>New Discussion</button>
				</div>
			<div class="add hide">
				<div class="row">
					<div class="col s12">
						<p class="flow-text">New Discussion</p>
						<form data-dest="<?php echo __url__.'/actions/forum.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
							<div class="input-field">
	        					<input type="text" required="true" name="topic" id="topic" class="validate">
	        					<label for="subject">Topic</label>
	        				</div>
							<div class="input-field">
	        					<textarea name="message" id="message" required="true" class="materialize-textarea"></textarea>
	        					<label for="message">Message</label>
	        				</div>
							<div class="input-field center-align">
								<input type="hidden" name="new" value="set">
								<input type="submit" value="SUBMIT" class="green darken-2 btn large">
							</div>
						</form>
					</div>
				</div>
			</div>				
			</div>	
			<div class="row col s12 m6 wow fadeInRight">
				<form class="form" data-dest="<?php echo __url__.'/actions/forum.actions.php' ?>" data-output="#discussion" form-type="dynamic" >
					<div class="input-field col s7">
						<input type="text" name="topic" id="topic" class="validate">
						<label for="subject">Discussion Search</label>
					</div>
					<div class="input-field col s5">
						<input type="hidden" name="search" value="set">
						<input type="submit" id="submit" value="SEARCH" class="btn blue darken-3 white-text">
					</div>
				</form>	
			</div>
		</div>
	<div class="wow fadeInUp" data-wow-delay="0.3s">	
	    <div class="col s12 wow fadeIn">
	      <ul class="tabs" style="overflow-x: hidden;">
	        <li class="tab col s6"><a href="#recent" class="active">Recent Discusions</a></li>
	        <li class="tab col s6"><a href="#mine" class="black-text">My Discussions</a></li>
	      </ul>
	    </div>
	    <div id="recent">
			<div class="row">
				<div class="col s12">
					<div class="card" id="discussion">
						<?php 
							if ($recents !== false) {
								foreach ($recents as $recent) {
						?>
							<div class="card-content">
								<h5><a  class="blue-text text-darken-4 spec-ajax" href="" data-output=".modal-content" data-toggle="modal" id="<?= $recent['id'] ?>" data-query="topic" data-dest="<?=  __url__.'/actions/forum.actions.php' ?>"><?= $recent['topic'] ?></a></h5>
								<p class="truncate"><?= $recent['request'] ?></p>
								<p><?= '<span class="green-text text-darken-1"> By: </span><span class="grey-text">'.$recent['membername'].'</span> | <span class="grey-text">'.date("jS M",strtotime($recent['dateMade'])).'</span>' ?></p>
							</div>
						<?php } } else { ?>
								<p class="center flow-text">
								 	No RECENT Discussions !
								</p>	
						<?php } ?>						
					</div>
				</div>
			</div>
	    </div>
	    <div id="mine">
			<div class="row">
				<div class="col s12">
					<div class="card">
						<?php 
							if ($mine !== false) {
								foreach ($mine as $min) {
						?>
							<div class="card-content">
								<h5><a class="blue-text text-darken-4 spec-ajax" href="" data-output=".modal-content" data-toggle="modal" id="<?= $min['id'] ?>" data-query="topic" data-dest="<?=  __url__.'/actions/forum.actions.php' ?>"><?= $min['topic'] ?></a></h5>
								<p class="truncate"><?= $min['request'] ?></p>
								<p><?= '<span class="green-text text-darken-1">Initiated: </span> <span class="grey-text">'.date("jS M @ H:i ",strtotime($min['dateMade'])).'GMT</span>' ?></p>
							</div>
						<?php } } else { ?>
								<p class="center flow-text">
								 	Please, you have NO Discussions !
								</p>	
						<?php } ?>
					</div>
				</div>
			</div>
	    </div>
	    </div>	    
	</div>
</main>