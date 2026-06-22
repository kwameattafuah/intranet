<div id="folderview">
<?php 
	if($media !== false)
		foreach ($media as $medium) { ?>
		  <div class="col s12 m4 wow flip">
		    <div class="card cursor spec-ajax" id="<?= $medium['event'] ?>" data-query="folder" data-dest="<?=  __url__.'/actions/media.actions.php' ?>" data-output="#folderview">
		      <div class="card-content" style="padding: 10px 0px 5px 15px">
		      	<div class="row" style="margin-bottom: 0px">
		      		<div class="col s2">
		      			<i class="material-icons medium blue-text text-darken-1">folder</i>
		      		</div>
		      		<div class="col s9 center" style="padding: 9px 0px 5px 15px">
		      			<span><?= $medium['event'] ?></span>
		      		</div>	
		      	</div>
		      </div>
		    </div>
		  </div>
		<?php } ?>

		<div class="col s12 m4 wow flip">
		    <div class="card cursor spec-ajax" id="2" data-query="imgvid" data-dest="<?=  __url__.'/actions/media.actions.php' ?>" data-output="#folderview">
		      <div class="card-content" style="padding: 10px 0px 5px 15px ">
		      	<div class="row" style="margin-bottom: 0px">
		      		<div class="col s2">
		      			<i class="material-icons medium blue-text text-darken-1">folder</i>
		      		</div>
		      		<div class="col s9 center" style="padding: 9px 0px 5px 15px">
		      			<span>CORPORATE VIDEOS</span>
		      		</div>	
		      	</div>
		      </div>
		    </div>
		  </div>		  
</div>