<?php 
	if ($directories !== false)
		foreach ($directories as $directory) {
?>
	<tr>
		<td><?php echo $directory['name']; ?></td>
		<td><?php echo $directory['description'] ?></td>
		<td><?php echo $directory['location']; ?></td>
		<td><?php echo $directory['number']; ?></td>
		<td><?php echo $directory['extension']; ?></td>
	</tr>	
<?php } ?>