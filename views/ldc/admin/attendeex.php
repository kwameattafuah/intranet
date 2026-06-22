<?php
	if ($peeps!==false){ 
		$no = 0; foreach($peeps as $peep) { ?>
		<tr class="cursor spec-ajax" data-query="attendee" data-value="<?= $peep['id'] ?>" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" data-toggle="modal">
			<td class="center"><?= ++$no.'.' ?></td>
			<td><?= ucwords(strtolower($peep['person'])) ?></td>
			<td><?= ucwords(strtolower($peep['dept'])) ?></td>
			<td><?= ucwords(strtolower($peep['title'])) ?></td>
			<td class="center"><?= (!is_null($peep['granted']))? '<span class="green-text">'.ucwords(strtolower($peep['granted'])) : '<span class="red-text"> Pending' ?></span></td>
		</tr>					
<?php 
		} 
	} 
?>	
