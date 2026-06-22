
<main>
  <div class="row">

  <div class="col s12 m3">
    <div class="row">
      <div class="card z-depth-2 yellow darken-2">
        <div class="card-content">
          <span class="card-title white-text center cursor">UPDATE CATEGORY</span>
          <div class="divider"></div>
        </div>
        <div class="card-body">
            <div class="row">
              <div class="col s12">
                <ul class="collapsible" data-collapsible="accordion">
                  <li>
                    <div class="collapsible-header"> MEDIA </div>
                    <div class="collapsible-body center grey lighten-3 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/media.actions.php' ?>" data-output=".disp" data-query="category" id="1"><span>Images</span>
                    </div>
                    <div class="collapsible-body center grey lighten-3 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/media.actions.php' ?>" data-output=".disp" data-query="category" id="2"><span>Videos</span>
                    </div>                        
                  </li>
                  <li>
                    <div class="collapsible-header"> DOCS / INFO </div>
                    <div class="collapsible-body center grey lighten-3  waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".disp" data-query="alerts"><span>Alert Information</span>
                    </div>  
                    <div class="collapsible-body center grey lighten-3  waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/infos.actions.php' ?>" data-output=".disp" data-query="all"><span>Information</span>
                    </div>                                       
                    <div class="collapsible-body center grey lighten-3  waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/docs.actions.php' ?>" data-output=".disp" data-query="docs"><span>Shared Documents</span>
                    </div>                                           
                  </li>
                  <li>
                    <div class="collapsible-header"> NEWS / LINKS </div>
                    <div class="collapsible-body center grey lighten-3  waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".disp" data-query="news" id="newsButton"><span>News</span>
                    </div>
                    <div class="collapsible-body center grey lighten-3  waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".disp" data-query="links"><span>Links</span>
                    </div>                        
                  </li>                                           
                  </ul>
              </div>
            </div>
          </div>
        </div>
      </div>  
  </div> 

  <div class="col m9 s12">
		<div class="row"> 
	  		<div class="col s12">
	    		<div class="card z-depth-2" style="overflow-y: auto;">
	      			<div class="card-content row disp">
                <span class="center-align card-title blue-text text-darken-3">WELCOME TO THE EDITORIAL ZONE</span>
                <p class="divider black-text"></p>
						    <p class="center-align flow-text red-text" style="padding-top: 5%">Please Be mindful, any changes made here would affect the general content stored and displayed on the Intranet!<br><br>Thank You!</p>
	      			</div>
	    		</div>
			</div>      			
		</div>
	</div>
<!-- actions -->
  <div class="action">
      <div class="fixed-action-btn horizontal <?= ($_SESSION['aj.gaclintra']['role']=='PROG' || $_SESSION['aj.gaclintra']['role']=='ADMIN' || $_SESSION['aj.gaclintra']['role']=='MAN' )? '' : 'hide' ?>">
        <a class="btn-floating btn-large red darken-2 waves-effect ">
          <i class="material-icons">menu</i>
        </a>
        <ul>
          <li>
            <a href="" class="btn-floating green waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Management" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".disp" data-query="mgt">
              <i class="material-icons">business</i>
            </a>
          </li>          
          <li>
            <a href="" class="btn-floating blue waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Directory" data-dest="<?php echo __url__.'/actions/directory.actions.php' ?>" data-output=".disp" data-query="dirs">
              <i class="material-icons">local_library</i>
            </a>
          </li>
          <li class="<?= ($_SESSION['aj.gaclintra']['role']=='PROG' || $_SESSION['aj.gaclintra']['role']=='ADMIN')? '' : 'hide' ?>">
            <a href="" class="btn-floating green waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Account Users" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".disp" data-query="users">
              <i class="material-icons">person</i>
            </a>
          </li>                     
          <li>
            <a href="" class="btn-floating yellow darken-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Pictures" data-extend-view="#images" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
              <i class="material-icons">image</i>
            </a>
          </li>
          <li>
            <a href="" class="btn-floating blue lighten-2 waves-effect waves-light spec-ajax tooltipped" data-position="top" data-delay="50" data-tooltip="Videos" data-extend-view="#videos" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
              <i class="material-icons">movie</i>
            </a>
          </li>                   
        </ul>
      </div>

<!-- start of extensions -->

  <!-- images -->
      <div id="images" class="hide">
        <div class="card">
          <div class="card-content">
            <div class="row">
              <div class="col s12">
                <p class="flow-text bold">IMAGE DETAILS</p>
                <form data-dest="<?php echo __url__.'/actions/media.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">          
                  <div class="input-field">
                    <input type="text" id="image-caption" name="caption" required class="validate" value="">
                    <label for="caption">Caption</label>
                  </div>
                  <div class="input-field">
                    <input type="text" id="image-event" name="event" class="validate" value="">
                    <label for="caption">Event</label>
                  </div>                
                  <div class="row">
                      <div class="input-field file-field col s12 m8">
                      <div class="btn green">
                        <span>image</span>
                        <input type="file" name="pics[]" multiple="true" required="true">
                      </div>
                      <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                      </div>
                          </div>
                        <div class="switch col s12 m4">
                          <h6>Set Status</h6>
                        <label>
                          <span class="red-text">INVISIBLE</span>
                          <input type="checkbox" name="status" >
                          <span class="lever"></span>
                          <span class="green-text">VISIBLE</span>
                        </label>
                    </div>
                  </div>
                  <div class="input-field center-align">
                    <input type="hidden" name="add" value="image">
                    <input type="submit" value="Add Media" class="blue darken-2 btn large">
                  </div>  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
  
  <!-- videos -->
      <div id="videos" class="hide">
        <div class="card">
          <div class="card-content">
            <div class="row">
              <div class="col s12">
                <p class="flow-text bold">VIDEO DETAILS</p>
                <form data-dest="<?php echo __url__.'/actions/media.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">          
                  <div class="input-field">
                    <input type="text" id="video-caption" name="caption" required class="validate" value="">
                    <label for="caption">Caption</label>
                  </div>
                  <div class="input-field">
                    <input type="text" id="video-event" name="event" class="validate" value="">
                    <label for="caption">Event</label>
                  </div>                  
                  <div class="row">
                          <div class="input-field col s12 m8">
                      <input type="text" id="vid" name="vid" required class="validate" value="">
                      <label for="vid">Embedded Url</label>
                          </div>                      
                        <div class="switch col s12 m4">
                          <h6>Set Status</h6>
                        <label>
                          <span class="red-text">INVISIBLE</span>
                          <input type="checkbox" name="status" >
                          <span class="lever"></span>
                          <span class="green-text">VISIBLE</span>
                        </label>
                    </div>
                  </div>                      
                  <div class="input-field center-align">
                    <input type="hidden" name="add" value="video">
                    <input type="submit" value="Add Media" class="blue darken-2 btn large">
                  </div>  
                </form>
              </div>
            </div>
          </div>
        </div>  
      </div>  

<!-- end of extensions -->

  </div>    
</div>
 

