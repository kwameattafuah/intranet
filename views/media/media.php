<?php  
	// initialise controller class
	$class = new Media;

	// get default display
	$media = $class->index();
?>

<main>
	<div class="row">
		<div class="col s12">
			<?php include("./folders.php") ?>
		</div>
	</div>
	
</main>	

<script>
  document.getElementById('links').onclick = function (event) {
      event = event || window.event;
      var target = event.target || event.srcElement,
          link = target.src ? target.parentNode : target,
          options = {index: link, event: event},
          links = this.getElementsByTagName('a');
      blueimp.Gallery(links, options);
  };
</script>