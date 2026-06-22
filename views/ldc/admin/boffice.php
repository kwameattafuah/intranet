<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldcbook.controller.php");

  // initialise controller class
  $class = new Ldc;

  $flags = $class->flags(null);
  $rooms = $class->rooms(null);
  $depts = $class->deptfetch();
   
?>  
<main>
	<div class="row">

		<div class="col s12 m6" style="border-right: solid 2px yellow">
			<div class="card-content">
			<p class="center-align flow-text blue-text text-darken-4">RESERVATIONS</p>
		      <form class="form" data-dest="<?= __url__.'/actions/ldcbook.actions.php' ?>" data-output=".modal-content" form-type="form" data-clear-input="true">
		        <div class="input-field">
		          <input type="text" id="purpose" name="purpose" required="true" >
		          <label for="purpose">Purpose</label>
		        </div>    
		        <div class="input-field">
		          <input type="text" name="booked_by" id="booked_by" required="true" >
		          <label for="booked_by">Booking For (section/unit)</label>
		        </div> 
		        <div class="input-field">
				    <select class="browser-default" name="dept" required>
				      <option value="" disabled selected>Choose Department</option>
				      <option value="">External Affairs</option>
				    <?php 
						if ($depts !== false)
							foreach ($depts as $dept) {
					?> 
				      <option value="<?= $dept['dept_id']?>" ><?= $dept['name'] ?></option>
				      <?php } ?>
				    </select>
		        </div>		        
		        <div class="row">
			        <div class="input-field col s12 m8 no-pad-left">
					    <select class="browser-default" name="room" required>
					      <option value="" disabled selected>Choose Room</option>
					    <?php 
							if ($rooms !== false)
								foreach ($rooms as $room) {
						?> 
					      <option value="<?= $room['id']?>" ><?= $room['room_name'] ?></option>
					      <?php } ?>
					    </select>
		        	</div>		        	
			        <div class="input-field col s12 m4 no-pad-right">
			          <input type="number" name="occupancy" id="occupancy" required="true" >
			          <label for="occupancy">Occupancy</label>
			        </div>  
			    </div>           
		        <div class="row">
                    <div class="input-field col s12 m6">
                      <span for="start_date">Start Timing</span>
                      <input type="datetime-local" id="start_date" name="start_date" required class="validate center">
                    </div>
                    <div class="input-field col s12 m6">
                      <span for="end_date">End Timing</span>
                      <input type="datetime-local" id="end_date" name="end_date" required class="validate center">
                    </div> 
                  </div>
		        <div class="row">
			        <div class="input-field center-align col s6">
			          <input type="hidden" name="booksave" value="set">
			          <button type="submit" class="btn green darken-2"> SAVE </button>
			        </div>
			        <div class="input-field center-align col s6">
			          <button type="reset" class="btn red darken-2"> RESET </button>
			        </div>
			    </div>    
		      </form>			
			</div>
		</div>

		<div class="col s12 m6">
			<div style="padding: 1% 5% 0% 5%">
				<p class="flow-text red-text text-darken-4 center-align">FLAGS</p>
				<div class="card">	
				  <div class="row">
				    <form class="form" data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>" data-output="tbody" form-type="dynamic" >
				        <div class="input-field col s12 m8">
				          <i class="material-icons prefix">search</i>
				          <input id="icon_prefix" type="text" name="item" class="validate">
				          <label for="icon_prefix">Purpose or Reservation Code</label>
				        </div>
						<div class="input-field col s12 m3 center-align">
			      			<input type="hidden" name="bookSearch" value="set">
						    <button type="submit" id="submit" class="btn green darken-2 white-text"> SEARCH </button> 
						</div>
				    </form>
				  </div>
				</div>  

				<div class="card">
					<div class="card-content">	
						<table class="responsive-table highlight">		
							<tbody style="overflow-y: auto;">
								<?php include("./flags.php") ?>
							</tbody>
						</table>
					</div>
				</div>
		</div>	

	</div>  
</main>