<?php  
	include("../layout/definition.php");
	include("../controllers/dashboard.controller.php");

	$class = new Dashboard;

	// view help info
	if (isset($_POST['query']) && $_POST['query'] == 'info') {
	// get content of info
	$info = $class->infoView($_POST['id']);
	
	// info content
	?>
	<div class="info-details">
	    <div class="row">
	      <div class="col s12">
	          <div class="card-content">
	            <h4><?php echo $info['subject'] ?></h4>
	            <span><?= $info['authority'] ?></span>
	            <div class="divider"></div>
	            <p><?php echo $info['details'] ?></p>
	          </div>
	      </div>
	    </div>
  	</div>
<?php }
	if (isset($_POST['query']) && $_POST['query'] == 'viewnews') {
		$news = $class->fetch_newsitem($_POST['id']);
		$newspics = $class->fetch_newspics($_POST['id']); { ?> 
			<div class="card-content">
				<span class="card-title flow-text red-text text-darken-2"><b><?= $news['headline'] ?></b></span>
				<p class="card-body wrap"><?= $news['content'] ?></p>
				<div class="row scrollable" style="max-height: 300px">
			      <?php foreach ($newspics as $pic) {  ?>
			        <div class="col s12 m4 newspic">
			          <div class="card">
			            <div class="card-image">
			              <img src="<?= __media__.'/news_gallery/'.$pic['frame'] ?>" class="spec-ajax" data-dest="<?php echo __url__.'/actions/home.actions.php' ?>" data-query="viewnewspic" data-output=".modal-content" data-value="<?= $pic['news_id'] ?>" id="<?= $pic['id'] ?>" data-toggle="modal">
			            </div> 
			          </div>
			        </div>  
			    <?php } ?>						
				</div>
			</div>
<?php  } }
	if (isset($_POST['query']) && $_POST['query'] == 'viewnewspic') {
		list($first,$pics) = $class->viewAllNewspics($_POST['id'],$_POST['value']); { ?> 
		<div class="owl-carousel owl-theme non-scrollable">
			<div class="item">
		    	<img src="<?= __media__.'/news_gallery/'.$first['frame'] ?>" class="image-fit">
		    	<p class="grey-text"><?= $first['caption'] ?></p>
		    </div>
			<?php foreach ($pics as $pic) { ?>
		    <div class="item">
		    	<img src="<?= __media__.'/news_gallery/'.$pic['frame'] ?>" class="image-fit">
		    	<p class="grey-text"><?= $pic['caption'] ?></p>
		    </div>
			<?php } ?>
		</div>

<?php  } 	}

// APPLICATIONS POPUP WINDOW
	if (isset($_POST['query']) && $_POST['query'] == 'apps') { ?>
		<div class="row parent">
			<div class="col s12 m4">
				<div class="card blue darken-1 white-text">
	                <div class="card-content row no-pad" style="padding-top:24px !important; margin-bottom: 0px">
	                	<div class="col s6 center">
		                  <a href="<?= __url__.'/ldc/' ?>" target="_blank" class="white-text waves-effect">
		                  	<p><i class="material-icons medium">explore</i></p>
		                  <span>Training</span></a>
		                </div>
		                <div class="col s6 center">
		                  <a href="<?= __url__.'/ldcroom/' ?>" target="_blank" class="white-text waves-effect">
		                  	<p><i class="material-icons medium">assignment</i></p>
		                  <span>Book Room</span></a>
		                </div>  
	                </div>
	                <div class="card-action center">
	                	<span class="black-text">Learning and Development Centre</span>
	                </div>
	            </div>    
            </div>
		
            <div class="col s12 m4 <?= ($_SESSION['aj.gaclintra']['role']=='PROG' || $_SESSION['aj.gaclintra']['role']=='ADMIN' || $_SESSION['aj.gaclintra']['role']=='SEC' )? '' : 'hide' ?>">
				<div class="card yellow darken-2 waves-effect white-text">
					<a class="white-text waves-effect" href="<?= __url__.'/correspondence/' ?>" target="_blank">
		                <div class="card-content center">
		                  <p><i class="material-icons medium">autorenew</i></p>
		                  <span class="flow-text">GACL Office Correspondence</span>
		                </div>
		            </a>
	            </div>    
            </div>

            <div class="col s12 m4 <?= ($_SESSION['aj.gaclintra']['role']=='RES' || $_SESSION['aj.gaclintra']['role']=='EDIT')? 'hide' : '' ?>">
				<div class="card orange darken-2 waves-effect white-text">
					<a class="white-text waves-effect" href="<?= __url__.'/confroom/' ?>" target="_blank">
		                <div class="card-content center">
		                  <p><i class="material-icons medium">room</i></p>
		                  <span class="flow-text">Conference Room Bookings</span>
		                </div>
		            </a>
	            </div>    
            </div>

		</div>

<?php } ?>

<script>
  $('.owl-carousel').owlCarousel({
    loop:true,
    items: 1,
    margin:10,
    nav:true,
    dots: false
  })
</script>