<!-- Top Story Area -->
<div class="row"> 
  <div class="col s12">
    <div class="card z-depth-2">
      <div class="card-content cursor">
        <span class="card-title"><b class="red-text text-darken-3"><?= $top['headline'] ?></b></span>
          <div class="divider"></div>
          <div class="wrap" style="max-height: 700px; padding-top: 5px">
            <p><?= $top['content'] ?></p><br/>
            <div class="owl-carousel owl-theme">
              <?php 
              if($images !== false){
                foreach ($images as $image) { ?>
                  <div class="item">
                    <img src="<?= __media__.'/news_gallery/'.$image['frame'] ?>" class="spec-ajax" data-dest="<?php echo __url__.'/actions/home.actions.php' ?>" data-query="viewnewspic" data-output=".modal-content" id="<?= $image['id'] ?>" data-value="<?= $image['news_id'] ?>" data-toggle="modal" alt="<?=$image['caption'] ?>">
                    </a>
                  </div>
                <?php } 
              }
              ?>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End of Top Story Area -->
<!-- News Feed Area -->
<?php 
  if($news !== false) { ?>
    <div class="row">
      <?php foreach ($news as $new) { 
        $pic = $class->imageView($new['id']); ?>
        <div class="col s12 m6">
          <div class="card blue lighten-2">
            <div class="card-image">
              <img src="<?= __media__.'/news_gallery/'.$pic['frame'] ?>">
            </div>
            <div class="card-content">
              <h5><a href="#" class="spec-ajax white-text" data-dest="<?php echo __url__.'/actions/home.actions.php' ?>" data-query="viewnews" data-output=".modal-content" id="<?= $new['id'] ?>" data-toggle="modal"><?= $new['headline'] ?></a></h5>
              <p class="wrap"><?= substr($new['content'],0,500).'...' ?></p>
            </div>  
          </div>
        </div>  
    <?php } ?>
    </div>
<?php } ?>      
<!-- End of News Feed Area -->  

<script>
  $('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:false,
    video:true,
    autoplay:true,
    autoplayTimeout:7000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1
        },
        1000:{
            items:3
        }
    }
  })
</script>
