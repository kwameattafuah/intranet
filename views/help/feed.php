<div class="row wow fadeInRight">
	<div class="col s12">
	<nav class="yellow darken-3">
	    <p class="center cursor" style="margin-top: 0px; padding-right: 20px">REQUEST HELP <i class="material-icons right">message</i> </p>
  	</nav>
    	<div class="card">
    		<div class="card-content" style="padding: 10px">
    			<form class="form" data-dest="<?php echo __url__.'/actions/help.actions.php' ?>" data-output=".modal-content" form-type="form" data-clear-input="true">
    				<div class="input-field">
    					<input type="text" required="true" name="name" id="name" class="validate">
    					<label for="name">Name</label>
    				</div>
                    <div class="input-field">
                        <input type="text" name="extension" id="extension" class="validate">
                        <label for="extension">Extension</label>
                    </div>                    
                    <div class="input-field">
                        <input type="text" required="true" name="subject" id="subject" class="validate">
                        <label for="subject">Subject</label>
                    </div>                                        
    				<div class="input-field">
    					<textarea name="description" id="description" required="true" placeholder="Type your request here" class="materialize-textarea"></textarea>
    					<label for="description">Description</label>
    				</div>
    				<div class="input-field center-align">
    					<input type="hidden" name="send" value="set">
    					<button type="submit" class="btn blue darken-3 white-text">SEND</button>
    			</form>
	        </div>
	      </div>
    </div>	  	
</div>
