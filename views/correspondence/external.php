<thead class="yellow darken-1">
	<tr class="cursor blue-text text-darken-4">
	  <th class="center">No.</th>
	  <th>Date Received</th>
	  <th>Document Source</th>
	  <th>Document Date</th>
	  <th>Sender</th>
	  <th>Subject</th>
	  <th>For Whom</th>
	  <th>Remarks</th>
	  <th>Receiver</th>
	</tr>
</thead>
<tbody style="overflow-y: auto">
<?php if ($docs !== false) { 
$no = 0; foreach($docs as $doc) { ?>
  <tr class="cursor spec-ajax" data-query="externaldoc" data-value="<?= $doc['id'] ?>" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-toggle="modal" id="<?= 'document'.$doc['id'] ?>">
    <td class="center"><?= ++$no.'.' ?></td>
    <td><?= date('M jS, Y',strtotime($doc['recdate'])) ?></td>
    <td><?= $doc['source'] ?></td>
    <td><?= date('jS M, Y',strtotime($doc['sdate'])) ?></td>
    <td><?= (is_null($doc['sender']))? $doc['source'] : $doc['sender'] ?></td>
    <td><?= $doc['subject'] ?></td>
    <td><?= $doc['to_whom'] ?></td>
	<td><?= $doc['remarks'] ?></td>
	<td><?= ($doc['receiver'] == 'Pending')? '<span class="red-text">Pending</span>': $doc['receiver'] ?></td>
  </tr>       
<?php } } ?>  
</tbody>
