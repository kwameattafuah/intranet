<?php  
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

	// initialise controller class
	$class = new Ldc;
	$_SESSION['aj.chart'] = 'PieChart';

	// get default
	$courses = $class->evaluatedcourses(null);		

	if ( isset($_POST['id']) && ( $_POST['id'] == 'coursematerial' || $_POST['id'] == 'chart') ) {
		$course = ($_POST['id'] != 'coursematerial')? $_POST['bsearch'] : $_POST['asearch'];
		$_SESSION['aj.chart'] = ($_POST['id'] == 'chart')? $_POST['asearch'] : $_POST['bsearch'];

		$sections = $class->fetch_score_sections($course);
?>
<div class="row">
	<p class="flow-text brown-text text-darken-1" style="padding-left: 3%"><?= $sections[0]['title'] ?></p>
	<?php 
	if (!empty($sections)) { 
	foreach ($sections as $section) {
		$thesection = $section['section_id'];
		echo '<h4 class="center-align">'.$section['caption'].'</h4>';
			$questions = $class->evaluations_questions($thesection); 
			foreach ($questions as $question) {
				$thequestion = $question['id'];
				$displays = $class->fetch_question_scores($course,$thesection,$thequestion); 
					foreach ($displays as $display) { ?>
						<div class="col m4 s12 ">
					      <div class="card">
					          <div id="material-pie-chart-<?= $thequestion ?>" style="height: 300px;">
					          </div>
					      </div>
					    </div>				
					  <!-- graph -->
					  <!-- include graph loader js -->
					<script src="<?php echo __ASSETS__.'/js/loader.js'; ?>"></script>
					<script type="text/javascript">
					  // pie chart
					        google.charts.load("current", {packages:["corechart"]});
					      google.charts.setOnLoadCallback(drawChart);
					      function drawChart() {
					        var data = google.visualization.arrayToDataTable([
					          ['Grade', 'Score'],
					          ['Grade A', <?= $display['grade_A'] ?>],
					          ['Grade B', <?= $display['grade_B'] ?>],
					          ['Grade C', <?= $display['grade_C'] ?>],
					          ['Grade D', <?= $display['grade_D'] ?>],
					          ['Grade E', <?= $display['grade_E'] ?>]
					        ]);

					        var options = {
					          title: "<?= $display['reads_as'] ?>",
					          is3D: false,
					        };

					        var chart = new google.visualization.<?= $_SESSION['aj.chart'] ?>(document.getElementById('material-pie-chart-<?= $thequestion ?>'));
					        chart.draw(data, options);
					      }
					  </script>										
	<?php	} } } } ?>

</div>
<?php } else { ?>

<main>
  <div class="row aejay"> 
      <div class="input-field col s12 m8">
        <select class="browser-default dynamic-dual" id="coursematerial" name="coursematerial" data-output="#aejay" data-query="coursematerial" data-dest="<?php echo __url__.'/views/ldc/admin/stats.php' ?>" dual-data="#chart">
			<option value="0" disabled selected>Please select a course</option>	
			<?php foreach ($courses as $course) { ?>
				<option value="<?= $course['id'] ?>"><?= $course['title'] ?></option>
			<?php } ?>
		</select>
      </div> 
      <div class="input-field col s10 m3 center">
        <select data-output="#aejay" class="browser-default dynamic-dual" id="chart" name="chart" data-query="chart" data-dest="<?php echo __url__.'/views/ldc/admin/stats.php' ?>" dual-data="#coursematerial">	
				<option value="PieChart">View as Pie Chart</option>
				<option value="BarChart">View as Bar Chart</option>
		</select>
      </div>
      <div class="input-field col s2 m1 center">
        <button type="button" class="btn print"><i class="material-icons">print</i></button>
      </div>
  </div> 
<?php } ?>   
  <div class="row" id="aejay">

  </div>
</main>