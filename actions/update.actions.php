<?php  
	include("../layout/definition.php");
	include("../controllers/update.controller.php");
	$class = new Update;

	if (isset($_POST['editnews'])) {
		if(isset($_POST['status'])){
			$status = 1;
		}else{
			$status = 0;
		}
		if(isset($_POST['topstory'])){
			$topstory = 1;
		}else{
			$topstory = 0;
		}
		$result = $class->newsedit($_POST['headline'],$_POST['content'],$_POST['url'],$status,$topstory,$_POST['editnews']);

		if ($result === true)
			echo '<script>Materialize.toast("News Item editted Successfully", 4000)</script>';
		else
			echo '<script>Materialize.toast("An unexpected error occured", 4000)</script>';
	}

	if (isset($_POST['addnews'])) {
		if(isset($_POST['status'])){
			$status = 1;
		}else{
			$status = 0;
		}
		if(isset($_POST['topstory'])){
			$topstory = 1;
		}else{
			$topstory = 0;
		}		
		$result = $class->newsadd($_FILES['pics'],$_POST['headline'],$_POST['content'],$_POST['url'],$status,$topstory);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">News added Successfully!</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured!</p>';		
	}

	if (isset($_POST['query']) && $_POST['query'] == 'newsdelete') {
		$result = $class->newsdelete($_POST['id']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		}else{
			echo '<script>Materialize.toast("News Item Deleted!", 4000)</script>';
		}
	}

	if (isset($_POST['query']) && $_POST['query'] == 'newspicdelete') {
		$result = $class->newspicdelete($_POST['id']);

		if ($result === false) {
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
		}else{
			echo '<p class="center-align green-text flow-text">News Item Image Deleted!</p>';
		}
	}	

	if (isset($_POST['query']) && $_POST['query'] == 'viewnewspic') {
		$pic = $class->viewNewspic($_POST['id']); { ?> 
			<img src="<?= __media__.'/news_gallery/'.$pic['frame'] ?>" style="width: 100%!important">
			<p><span><?= $pic['caption'] ?></span><span class="cursor right spec-ajax red-text" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" id="<?= $pic['id'] ?>" data-query="newspicdelete" data-fadeOut=".newspic">
				<i class="material-icons">delete</i></span></p>
<?php  } 	}	
	if (isset($_POST['editlink'])) {

		$result = $class->linkedit($_POST['linktag'],$_POST['linkurl'],$_POST['editlink']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Link Item editted Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
	}

	if (isset($_POST['addlink'])) {		
		$result = $class->linkadd($_POST['linktag'],$_POST['linkurl']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Link Item added Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';		
	}

	if (isset($_POST['query']) && $_POST['query'] == 'linkdelete') {
		$result = $class->linkdelete($_POST['id']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		}else{
			echo '<script>Materialize.toast("Link Item Deleted!", 4000)</script>';
		}
	}

	if (isset($_POST['query']) && $_POST['query'] == "news") {
		$news = $class->newsfetch();

		// include display
		include("../views/update/news.php");

	}elseif (isset($_POST['query']) && $_POST['query'] == "links") {
		$links = $class->linksfetch();

		// include display
		include("../views/update/links.php");
	}elseif (isset($_POST['query']) && $_POST['query'] == "alerts") {
		$alerts = $class->alertsfetch();

		// include display
		include("../views/update/alerts.php");
	}elseif (isset($_POST['query']) && $_POST['query'] == "users") {
		list($users,$depts,$roles) = $class->usersfetch();

		// include display
		include("../views/update/users.php");
	}elseif (isset($_POST['query']) && $_POST['query'] == "mgt") {
		$mgts = $class->mgtsfetch();

		// include display
		include("../views/update/mgt.php");
	}

	if (isset($_POST['editalert'])) {

		$result = $class->editalert($_POST['subject'],$_POST['details'],$_POST['authority'],$_POST['editalert']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Alert Information updated Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
	}

	if (isset($_POST['addalert'])) {		
		$result = $class->addalert($_POST['subject'],$_POST['details'],$_POST['authority']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Alert Information added Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';		
	}

	if (isset($_POST['query']) && $_POST['query'] == 'alertdelete') {
		$result = $class->alertdelete($_POST['id']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		}else{
			echo '<script>Materialize.toast("Alert Information Deleted!", 4000)</script>';
		}
	}

	if (isset($_POST['edituser'])) {
		if (!isset($_POST['password']) || empty($_POST['password'])) {
			$result = $class->edituser($_POST['name'],$_POST['email'],$_POST['username'],$_POST['staff_id'],$_POST['dept'],$_POST['role'],$_POST['edituser']);

			if ($result === true)
				echo '<p class="center-align green-text flow-text">User Information updated Successfully</p>';
			else
				echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
		}elseif ($_POST['password'] !== $_POST['cpassword']){
			echo '<p class="center-align red-text flow-text">Passwords don\'t match!"</p>';	
		} else {
			$result = $class->resetuser($_POST['name'],$_POST['email'],$_POST['username'],$_POST['staff_id'],$_POST['password'],$_POST['dept'],$_POST['role'],$_POST['edituser']);

			if ($result === true)
				echo '<p class="center-align green-text flow-text">User Information was reset Successfully</p>';
			else
				echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
		}
	}

	if (isset($_POST['adduser'])) {		
		if ($_POST['password'] !== $_POST['cpassword']){
			echo '<p class="center-align red-text flow-text">Passwords don\'t match!"</p>';	
		}else{		
		$result = $class->adduser($_POST['name'],$_POST['email'],$_POST['username'],$_POST['staff_id'],$_POST['password'],$_POST['dept'],$_POST['role']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">User added Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';	
		}	
	}

	if (isset($_POST['query']) && $_POST['query'] == 'userdelete') {
		$result = $class->userdelete($_POST['id']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		}else{
			echo '<script>Materialize.toast("User Successfully Deleted!", 4000)</script>';
		}
	}	

	if (isset($_POST['editmgt'])) {
		$result = $class->editmgt($_POST['name'],$_POST['position'],$_POST['rank'],$_POST['editmgt']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Management Member Information was reset Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
	}

	if (isset($_POST['addmgt'])) {				
		$result = $class->addmgt($_POST['name'],$_POST['position'],$_POST['rank']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Management Member added Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';		
	}

	if (isset($_POST['query']) && $_POST['query'] == 'mgtdelete') {
		$result = $class->mgtdelete($_POST['id']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		}else{
			echo '<script>Materialize.toast("Management Member Successfully Deleted!", 4000)</script>';
		}
	}					

?>