<?php
	if ($flags!==false){ 
		$no = 0; foreach($flags as $flag) { 
		if($flag['flag'] == 'C')
			$flagged = '<span class="red-text">CLASH</span>';
		elseif($flag['flag'] == 'S')
			$flagged = '<span class="blue-text text-darken-1">OVERSIZE</span>';
		else
			$flagged = '<span class="yellow-text text-darken-3">pending</span>'
				
	?>
		<tr class="cursor spec-ajax" data-query="flag" data-value="<?= $flag['id'] ?>" data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>" data-output=".modal-content" data-toggle="modal">
			<td class="center"><?= ++$no.'.' ?></td>
			<td><?= '<span class="brown-text text-darken-1">'.$flag['purpose'].'</span> - '.$flag['booked_by'] ?></td>
			<td class="center"><?= $flagged ?></td>
		</tr>					
<?php 
		} 
	}else{ ?>
		<tr>
			<td colspan="3"> <p class="brown-text">No Pending Authorisations or Flags! </p></td>
		</tr>
<?php	}
?>	
