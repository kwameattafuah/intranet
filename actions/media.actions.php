<?php  
	include("../layout/definition.php");
	include("../controllers/media.controller.php");

	$class = new Media;

	// other images and videos
	if (isset($_POST['query']) && $_POST['query'] == 'imgvid'){
		//get details
		if ($_POST['id'] === 1)
			$images = $class->category($_POST['id']);
		else
			$videos = $class->category($_POST['id']);
		// display		
		include("../views/media/medium.php");

 	} elseif (isset($_POST['query']) && $_POST['query'] == 'video'){ 
		//get video details
		$video = $class->view($_POST['id']);
		// display
	?>
	<div class="card wow fadeIn">
		<div class="card-image">
			<?= $video['frame'] ?>
		</div>
	</div>
<?php } elseif (isset($_POST['query']) && $_POST['query'] == 'folder'){
		//get image details
		$images = $class->event($_POST['id']);
		// display		
		include("../views/media/medium.php");
}
	if(isset($_POST['add']) && $_POST['add']=='image'){
		if(isset($_POST['status'])){
			$status = 1;
		}else{
			$status = 0;
		}
		$result = $class->addImage($_FILES['pics'],$status, $_POST['caption'],$_POST['event'],1);

		if ($result === false) {
			echo '<p class="flow-text center-align red-text">An unexpected error occured! Please check selection and try again</p>';
		}else{
			echo '<p class="flow-text center-align green-text">Image(s) added Successfully</p>';
		}	
	}elseif(isset($_POST['add']) && $_POST['add']=='video'){
		if(isset($_POST['status'])){
			$status = 1;
		}else{
			$status = 0;
		}
		$result = $class->addVideo($_POST['vid'],$status, $_POST['caption'],$_POST['event'],2);
		if ($result === false) {
			echo '<p class="flow-text center-align red-text">An unexpected error occured! Please try again</p>';
		}else{
			echo '<p class="flow-text center-align green-text">Video added Successfully</p>';
		}
	}

	if (isset($_POST['query']) && $_POST['query'] == "delete") {
		$result = $class->delete($_POST['id']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		}else{
			echo '<script>Materialize.toast("Media Deleted!", 4000)</script>';
		}
	}	

	if (isset($_POST['update'])) {
		if(isset($_POST['status'])){
			$status = 1;
		}else{
			$status = 0;
		}
		$result = $class->editImage($status, $_POST['caption'],$_POST['event'],$_POST['update']);
		if ($result === false) {
			echo '<p class="flow-text center-align red-text">An unexpected error occured! Please try again</p>';
		}else{
			echo '<p class="flow-text center-align green-text">Image updated Successfully</p>';
		}
	}

	if (isset($_POST['query']) && $_POST['query'] == "category") {
		$media = $class->category($_POST['id']);

		// include display
		include("../views/update/media.php");
	}	
	

?>