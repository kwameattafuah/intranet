<?php 
if (isset($_GET['aj_news'])) { 
	// initialise controller class
	$class = new Docs;
	$news = $class->fetch_newsitem($_GET['aj_news']);
	$newspics = $class->fetch_newspics($_GET['aj_news']);
?>
<main>
	<div class="container-fluid">
		<div class="card">
			<div class="card-content">
				<span class="card-title flow-text red-text text-darken-2"><b><?= $news['headline'] ?></b></span>
				<p class="card-body wrap"><?= $news['content'] ?></p>
				<div class="row scrollable" style="max-height: 500px">
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
		</div>
	</div>


<?php }else{
	if ($newx !== false)
		foreach ($newx as $news) {
?>
	<tr class="wow zoomIn">
		<td><a href="<?= ($news['url'] == '#')? '?aj_news='.$news['id'] : $news['url'] ?>" class="black-text"><?php echo $news['headline']; ?></a></td>		
		<td class="grey-text"><?php echo $news['dateCreated'] ?></td>
	</tr>	
<?php } } ?>
