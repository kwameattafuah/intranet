<?php
  // initialize class
  $class = new Dashboard;

  list($infos,$top,$images,$links,$news) = $class->index();

?>
<main>
  <div class="row">
    <div class="col s12 m9">
      <?php include("./left.php"); ?>
    </div>
    <div class="col s12 m3">
      <?php include("./right.php"); ?>
    </div>
  </div>      
  

