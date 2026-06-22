<thead>
	<tr class="blue-text text-darken-3">
		<th>Name</th>
		<th>Location</th>
		<th>Number</th>
		<th>Extension</th>
	</tr>
</thead>
<tbody class="wow fadeIn">
<?php 
	if ($depts !== false){
		foreach ($depts as $dept) {
			$directorys = $class->contacts($dept['dept_id'],$directories);
?>
	<tr>
		<td colspan="4" class="bold green-text text-darken-3"><h5><?php  echo $dept['name'];  ?></h5></td>
	</tr>
	<?php 
	if ($directorys !== false){
		foreach ($directorys as $directory) { ?>
<tr class="wow fadeInUp">
	<td><?php echo $directory['name']; ?></td>
	<td><?php echo $directory['location']; ?></td>
	<td><?php echo $directory['number']; ?></td>
	<td><?php echo $directory['extension']; ?></td>
</tr>
	<?php } } } } ?>	
</tbody>
