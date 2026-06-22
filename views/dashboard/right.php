<?php if (isset($_POST['query']) && $_POST['query'] == 'flights') {
        if ($_POST['value'] == 'arrivals') {
  ?>
          <div class="airportia-widget">
              <iframe scrolling="no" frameborder="0" style="border:0; width: auto; height: 95%; min-height: 650px; margin:0; padding:0;"
                      src="https://www.airportia.com/widgets/airport/acc/arrivals"></iframe>
              <div style="font-family: arial; font-size:10px; color:#3f9bdc; width: 100%; text-align: center; margin-top: 2px; padding-top: 5px; border-top: 1px solid #65747e;"></div>
          </div>
<?php } elseif ($_POST['value'] == 'departures') { ?>
        <div class="airportia-widget">
            <iframe scrolling="no" frameborder="0" style="border:0; width: auto; height: 95%; min-height: 650px; margin:0; padding:0;"
                    src="https://www.airportia.com/widgets/airport/acc/departures"></iframe>
            <div style="font-family: arial; font-size:10px; color:#3f9bdc; width: 100%; text-align: center; margin-top: 2px; padding-top: 5px; border-top: 1px solid #65747e;"></div>
        </div>
<?php } 
    } else { 
  ?>
  <div class="card z-depth-1 wow fadeInUp" data-wow-delay="0.2s">
    <div class="card-body">
      <ul class="collapsible" data-collapsible="accordion">
        <li>
          <div class="collapsible-header cursor">
            <span class="flow-text blue-text text-darken-4"><i class="material-icons" style="padding-right: 3%">rss_feed</i>Useful Links</span>
          </div>
          <div class="collapsible-body grey lighten-3" style="padding: 10px">
            <ul>
              <?php 
              if($links !== false)
              foreach ($links as $link){ ?>
                <li style="padding: 3px 0px 3px 0px"> 
                  <a href="<?php echo $link['url'] ?>" target="_blank"><?= $link['name'] ?></a>
                </li>
              <?php } ?>
            </ul>
          </div>
        </li>
        <li>
          <div class="collapsible-header cursor center">
            <span class="flow-text">Flights Schedule</span>
          </div>
          <div class="collapsible-body" style="padding: 15px">
            <ul>
                <li class="flow-text">
                  <a class="waves-effect spec-ajax black-text" href="#" data-query="flights" data-value="arrivals" data-dest="<?php echo __url__.'/views/dashboard/right.php' ?>" data-output=".modal-content" data-toggle="modal"><i class="material-icons left red-text">flight_land</i><span>Arrivals</span></a>
                </li>              
                <li class="flow-text">
                  <a class="waves-effect spec-ajax black-text" href="#" data-query="flights" data-value="departures" data-dest="<?php echo __url__.'/views/dashboard/right.php' ?>" data-output=".modal-content" data-toggle="modal"><i class="material-icons left green-text">flight_takeoff</i><span>Departures</span></a>
                </li> 
            </ul>
          </div>
        </li>        
      </ul>         
      </div>
    </div>  

    <div class="card z-depth-2 red wow fadeInUp" data-wow-delay="0.1s">
      <div class="card-content scrollable" style="max-height: 300px; overflow-y: auto; padding: 15px">
        <span class="card-title white-text center cursor"><b>ALERTS</b></span>
        <div class="divider"></div>
       <?php 
       if($infos !== false) 
       foreach ($infos as $info){ ?>
          <div class="card-panel grey lighten-3 cursor spec-ajax" data-output=".modal-content" data-toggle="modal" id="<?= $info['id'] ?>" data-query="info" data-dest="<?=  __url__.'/actions/home.actions.php' ?>" style="padding: 10px; opacity: 0.7">
            <span class="bold"><?= $info['subject'] ?></span>
            <p>From: <?= $info['authority'] ?></p>
          </div>
        <?php }else{ ?> 
          <div class="row center" style="padding-top: 20px">
            <p class="bold">None Available !!</p>
          </div>
        <?php } ?> 
      </div>
    </div>

<?php } ?>
