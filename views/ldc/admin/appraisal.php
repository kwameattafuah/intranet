<?php

  if (isset($_GET['appraised_participant'])) { 

  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

  // include head
  include("../../../layout/head.php");

   // initialise controller class
   $class = new Ldc; 

    $theid = $_GET['appraised_participant'];
    $thedept = $_GET['aj_dept'];
    $person = $class->history($theid);
    $person = $person[0]['name'];

    $thecourse = $_GET['aj'] - 146;
    $thecourse /= 201;

    $sections = $class->appraisal_sections();
?>
    <div class="container">
      <div class="card">
        <nav class="green darken-2">
          <div class="nav-wrapper" style="padding-left: 3%; padding-right: 5%;">
            <p>
              <span style="font-size: 140%"><a href="#"><?= ucwords(strtolower($person)) ?></a></span>
              <span class="right"><?= ucwords(strtolower($thedept)) ?></span>
            </p>  
          </div>
        </nav>  

      <div class="card-content">
        <form class="form"> 
          <table class="table-responsive striped">
          <?php $j=0; 
            foreach ($sections as $section) { 
            $questions = $class->participant_questions($thecourse,$section['id'],$theid);
          ?>
            <thead>
              <th class="center flow-text" colspan="2"><?= $section['caption'] ?></th>
            </thead>  
            <tbody>
            <!-- Question Template -->    
            <?php foreach ($questions as $question) {
              ++$j;
              switch ($question['grade']) {
                case 'A':
                  $score = '<span class="green-text">STRONGLY AGREE</span>';
                  break;
                case 'B':
                  $score = '<span class="green-text text-darken-2">AGREE</span>';
                  break;
                case 'D':
                  $score = '<span class="red-text text-darken-2">DISAGREE</span>';
                  break;
                case 'E':
                  $score = '<span class="red-text">STRONGLY DISAGREE</span>';
                  break;    
                default:
                  $score = '<span>NEUTRAL</span>';
                  break;
              } 
            ?>        
              <tr>
                <td> 
                  <p> <?= $j.'. '.$question['reads_as'] ?> </p> 
                </td>
                <td class="center">
                    <?= $score ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          <?php } $questions = $class->participant_comments($thecourse,$theid); ?>  
          </table>
        </form> 
          <br><h5>Suggestions and Comments</h5><br>

          <?php foreach ($questions as $question) { ++$j; ?>  
            <div class="input-field">
              <p class="flow-text brown-text"><?= $j.'. '.$question['reads_as'] ?></p>
                <p style="padding-left: 3%"><?= $question['remarks'] ?></p>
                <br>
              </div> 
          <?php } 
            list($remarksmade,$show) = $class->fetch_feed($thecourse,$theid);

            if ($remarksmade !== false) { 
              echo '<div class="row"> <br><h5>Remarks Made</h5><br>'; 
              foreach ($remarksmade as $remarkmade) { ?>
              <p class="wrap"><?= ucfirst($remarkmade['details']) ?><br><small class="grey-text"><?= date("jS M. 'y",strtotime($remarkmade['when_was'])) ?></small></p><br>
          <?php } echo '</div>'; } ?>                
      </div>
    </div>  
<?php  }else{
?>
  <table class="responsive-table highlight">
    <thead>
      <tr class="cursor">
        <th class="center">No.</th>
        <th>Name</th>
        <th>Department</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        if ($participants !== false) {
          $no = 0; foreach($participants as $participant) {
            $ind = $participant['individual'];
            $dept = $participant['dept'];

            $aj = 201;
            $aj *= $participant['course_id'];
            $aj += 146; ?>

          <tr>
            <td class="center"><?= ++$no.'.' ?></td>
            <td><a href="<?= __url__.'/ldc/appraisal/?appraised%20participant='.$ind.'&aj='.$aj.'&aj_dept='.$dept ?>" target="_blank"><?= ucwords(strtolower($participant['name'])) ?></a></td>
            <td><?= $participant['dept'] ?></td>
          </tr>
      <?php
           }
        } else { ?>
          <tr>
            <td colspan="3" class="center-align red-text text-darken-2">No Appraisals yet!</td>
          </tr>
    <?php  } 
      ?>  
    </tbody>
  </table>

<?php } ?>