<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
	    <a class="prev">‹</a>
	    <a class="next">›</a>
	    <a class="close">×</a>
	    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<?php 
	if (isset($images) && $images !== false) {
		$event = $images['0']['event'];
	 ?>
		<div class="row"> 
		  <div class="col s12">
		    <div class="card z-depth-2">
		      <div class="card-content cursor">
		        <span class="card-title"><b><?= $event ?></b><i class="material-icons grey-text text-darken-2 right">collections</i></span>
		        <div id="links" class="row scrollable" style="max-height: 700px; overflow-y: auto;">
		        <?php 
		        if($images !== false){
		          foreach ($images as $image) { ?>
		              <a href="<?= __media__.'/gallery/'.$image['frame'] ?>" title="<?=$image['caption'] ?>">
		                <div class="col s12 m4 media wow zoomIn">
		                <div class="card">
		                  <div class="card-image">
		                    <img src="<?= __media__.'/gallery/'.$image['frame'] ?>">
		                  </div>
		                </div>
		              </div></a>
		          <?php } 
		        } else {
		          echo '<p class="flow-text center-align">No Images Available</p>';
		        }
		        ?>
		      </div>
		      </div>
		    </div>
		  </div>
		</div>
	<?php } elseif (isset($videos) && $videos !== false) {
		foreach ($videos as $video) { ?>
		<div class="col s12 m6 media wow zoomIn">
		    <div class="card">
		      <div class="card-image spec-ajax" data-output=".modal-body" data-toggle="modal" id="<?= $video['id'] ?>" data-query="video" data-dest="<?=  __url__.'/actions/media.actions.php' ?>">
		      	<?= $video['frame'] ?>
		      </div>
		      <div class="card-content cursor hide-on-med-and-down spec-ajax" data-output=".modal-body" data-toggle="modal" id="<?= $video['id'] ?>" data-query="video" data-dest="<?=  __url__.'/actions/media.actions.php' ?>"style="padding: 5px 0px 5px 15px ">
		      	<p class="blue-text text-darken-5 center"><?= $video['caption'] ?></p>
		      	<p class="grey-text center"><?= $video['event'] ?></p>
		      </div>
		    </div>
		</div>    
	<?php } }elseif (isset($media) && $media !== false) { ?>
		<div class="row"> 
		  <div class="col s12">
		    <div class="card z-depth-2">
		      <div class="card-content cursor">
		        <span class="card-title"><b><?= $label ?></b><i class="material-icons grey-text text-darken-2 right">collections</i></span>
		        <div id="links" class="row scrollable" style="max-height: 400px; overflow-y: auto;">
		        <?php if($media !== false){
		          foreach ($media as $medium) { ?>
		              <a href="<?= __media__.'/gallery/'.$medium['frame'] ?>" title="<?=$medium['caption'] ?>">
		                <div class="col s12 m4 media wow zoomIn">
		                <div class="card">
		                  <div class="card-image">
		                    <img src="<?= __media__.'/gallery/'.$medium['frame'] ?>">
		                  </div>
		                </div>
		              </div></a>
		          <?php } } ?>
		      </div>
		      </div>
		    </div>
		  </div>
		</div>
	<?php } ?>
</div>

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