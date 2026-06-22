<thead class="yellow darken-1">
  <tr class="cursor blue-text text-darken-4">
    <th class="center">No.</th>
    <th>Date Dispatched</th>
    <th>Document Source</th>
    <th>Document Date</th>
    <th>Subject</th>
    <th>To Whom</th>
    <th>Department</th>
    <th>Remarks</th>
    <th>Received By</th>
  </tr>
</thead>
<tbody style="overflow-y: auto">
<?php if ($docs !== false) { 
$no = 0; foreach($docs as $doc) { ?>
  <tr class="cursor spec-ajax" data-query="dispatchdoc" data-value="<?= $doc['id'] ?>" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-toggle="modal" id="<?= 'document'.$doc['id'] ?>">
    <td class="center"><?= ++$no.'.' ?></td>
    <td><?= date('M jS, Y',strtotime($doc['disdate'])) ?></td>
    <td><?= $doc['source'] ?></td>
    <td><?= date('jS M, Y',strtotime($doc['sdate'])) ?></td>
    <td><?= $doc['subject'] ?></td>
    <td><?= $doc['to_whom'] ?></td>
    <td><?= $doc['recepient'] ?></td>
    <td><?= $doc['remarks'] ?></td>
    <td><?= ($doc['receiver'] == 'Pending')? '<span class="red-text">Pending</span>': $doc['receiver'] ?></td>
  </tr>       
<?php } } ?>  
</tbody>
