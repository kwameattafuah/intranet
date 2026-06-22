<?php 
	if ($infos !== false)
	foreach ($infos as $info) 
{ ?>
	<div class="cursor card z-depth-2 blue lighten-3 wow fadeInUp">
		<div class="card-content spec-ajax" data-output=".modal-content" data-toggle="modal" id="<?= $info['id'] ?>" data-query="info" data-dest="<?=  __url__.'/actions/help.actions.php' ?>">
	        <span class="card-title white-text center flow-text bold">
	        	<?= $info['heading'] ?>
	        </span>
	        <div class="divider"></div>
        	<p class="grey-text text-darken-3" style="padding-top: 15px"><?= str_replace(';', '.<br/><br>', $info['body']) ?></p>
		</div>
	</div>
<?php }else{ ?>
	<div class="cursor row wow zoomIn">
		<p class="red-text center flow-text"> No Information to Display !</p>
	</div>
<?php } ?>