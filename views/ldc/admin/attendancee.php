<?php 
  if ($regs !== false) {
    $no = 0; foreach($regs as $reg) {?>
    <tr>
      <td class="center"><?= ++$no.'.' ?></td>
      <td><?= $reg['name'] ?></td>
      <td><?= $reg['dept'] ?></td>
      <td><?= ($reg['status'] !== 0)? '<span class="green-text"> Present' : '<span class="red-text"> Absent' ?></span></td>      
      <td class="center"><?= date('jS M, h:i a', strtotime($reg['date'])) ?></td>
    </tr>
<?php
     }
  } 
?>  
