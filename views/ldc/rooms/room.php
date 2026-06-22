<?php 
	if (!empty($room)) {
		if($room['flag'] == 'C'){
			$flagged = '<span class="red-text">Clash Timing</span>';
		}elseif($room['flag'] == 'S'){
			$flagged = '<span class="red-text text-darken-1">Occupancy Overload</span>';
		}else{
			$flagged ='<span class="grey-text">N/A</span>';
		}

		if($room['status'] == 1){
			$state = '<span class="green-text">Approved</span>';
		}elseif($room['status'] == 0){
			$state = '<span class="red-text text-darken-1">Denied</span>';
		}else{
			$state ='<span class="grey-text">Pending</span>';
		}		
?>
	<p class="flow-text bold green-text text-darken-4"><?php echo ucwords($room['purpose']) ?></p>
	<hr class="divider">
	<div class=" row">
  <div class="col s12 m6">
    <p><b class="blue-text text-darken-4">Room:</b><br> <?php echo ucwords($room['room_name']) ?></p>
    <p><b class="blue-text text-darken-4">Start Date/Time:</b><br> <?php echo date('jS M, Y @ H:i A', strtotime($room['start_dt'])); ?></p>
    <p><b class="blue-text text-darken-4">End Date/Time:</b><br> <?php echo date('jS M, Y @ H:i A', strtotime($room['end_dt'])); ?></p>
    <p><b class="blue-text text-darken-4">Occupancy:</b><br> <?php echo $room['occupancy'].' people' ?></p>
  </div>  
  <div class="col s12 m6">
  	<p><b class="blue-text text-darken-4">Booked By:</b><br> <?php echo ucfirst($room['booked_by']) ?></p>
    <p><b class="blue-text text-darken-4">Department:</b><br> <?php echo ucwords($room['dept']) ?></p>
    <p><b class="blue-text text-darken-4">Date Booked:</b><br> <?php echo date('jS M, Y @ H:i', strtotime($room['book_date'])).' GMT'; ?></p>
    <p><b class="blue-text text-darken-4">Approval:</b><br> <?= $state ?></span></p>
    <p><b class="blue-text text-darken-4">Issues:</b><br> <?= $flagged ?></span></p>    
  </div> 
  <div class="col s12 <?= (!empty(trim($room['comments'])))? '' : 'hide' ?> ">
    <p class="blue-text text-darken-4 wrap"><b>Comment(s):</b><br> <?php echo $room['comments'] ?></p>
  </div> 
</div>
<?php	} ?>
	