<?php
// include definition
include("../../../layout/definition.php");

include("../../../controllers/ldc.controller.php");

 // initialise controller class
 $class = new Ldc; 

  list($courses,$appraisals) = $class->evaluations($_SESSION['aj.ldc']['ldcid']);  

?>  	

	<div class="container-fluid">
        <ul class="collapsible wow fadeInRight" data-wow-delay="0.2s" data-collapsible="accordion">
          <li>
            <div class="collapsible-header yellow lighten-2 center">
            <span class="bold">COURSE EVALUATIONS</span>
            </div>
            <div class="collapsible-body no-pad">
              	<table class="responsive-table highlight">
					<tbody>
						<?php 
							if ($courses !== false) {
								$aj = 2014;
								foreach ($courses as $course) {
								$aj *= $course['id'];
								$aj += 16;
						?>
							<tr>
								<td class="pointer" style="padding-left: 5%; padding-right: 5%"><a href="<?= __url__.'/ldc/evaluations/?course%20evaluation&aj='.$aj ?>" target="_blank"><?php echo $course['title']; ?></a></td>
							</tr>	
						<?php } } ?>
					</tbody>
				</table>
            </div>
          </li>
        </ul>
        <ul class="collapsible wow fadeInUp" data-wow-delay="0.5s" data-collapsible="accordion">  
          <li>
            <div class="collapsible-header blue lighten-2 center">
            <span class="bold">PERFORMANCE APPRAISALS</span>
            </div>
            <div class="collapsible-body no-pad">
              	<table class="responsive-table highlight">
					<tbody>
						<?php 
							if ($appraisals !== false){
								$aj = 1420;
								foreach ($appraisals as $appraisal) {
								$aj *= $appraisal['id'];
								$aj += 61;
						?>
							<tr>
								<td class="pointer" style="padding-left: 5%; padding-right: 5%"><a href="<?= __url__.'/ldc/evaluations/?performance%20appraisal='.$appraisal['title'].'&aj='.$aj ?>" target="_blank"><?php echo $appraisal['title']; ?></a></td>
							</tr>	
						<?php } } ?>
					</tbody>
				</table>
            </div>
          </li>              
        </ul>
	</div>		

<?php 	
		// include foot
	include("../../../layout/foot.php");
?>	