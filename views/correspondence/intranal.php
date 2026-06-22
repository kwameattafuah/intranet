<thead class="yellow darken-1">
  <tr class="cursor blue-text text-darken-4">
    <th class="center">No.</th>
    <th>Code</th>
    <th>Description</th>
    <th>Subject Matter</th>
    <th>Document Date</th>
    <th>From Whom</th>
    <th>Minuted To</th>
    <th>Action</th>
    <th>Status</th>
  </tr>
</thead>
<tbody style="overflow-y: auto">
<?php if ($docs !== false) { 
$no = 0; foreach($docs as $doc) { ?>
  <tr class="cursor spec-ajax" data-query="intranaldoc" data-value="<?= $doc['id'] ?>" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-toggle="modal" id="<?= 'document'.$doc['id'] ?>">
    <td class="center"><?= ++$no.'.' ?></td>
    <td><?= $doc['code'] ?></td>
    <td><?= $doc['subject'] ?></td>
    <td><?= $doc['source'] ?></td>
    <td><?= date('jS M, Y',strtotime($doc['sdate'])) ?></td>
    <td><?= $doc['to_whom'] ?></td>
    <td><?= $doc['receiver'] ?></td>
    <td><?= $doc['remarks'] ?></td>
    <td><?= ($doc['state'] == 0)? '<span class="red-text">Not Completed</span>': '<span class="green-text">Completed</span>' ?></td>
  </tr>       
<?php } } ?>  
</tbody>
