<?php 
	if ($infos !== false)
		foreach ($infos as $info) {
?>
	<tr class="wow zoomIn">
		<td><a  href="<?= __docs__.'/information/'.$info['source'] ?>" ><?php echo $info['name']; ?></a></td>		
		<td><?php echo $info['date_modified'] ?></td>
		<td><?php echo $info['modifier']; ?></td>
	</tr>	
<?php } ?>