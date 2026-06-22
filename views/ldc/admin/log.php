<?php
  // initialise controller class
  $class = new Ldc;

  date_default_timezone_set("Africa/Accra");
  $dated = date("Y-m-d");

  $value = $_SESSION['aj.ldc']['course'];
  $course = $class->viewcourse($value); 
  $all = $class->courseattendants($value); 
?>
<section>
  <nav>
    <div class="nav-wrapper green z-depth-2">
      <a href="#!" class="brand flow-text" style="padding-left: 30%; padding-right: 20%">Learning & Development Centre Attendance</a>
    </div>
  </nav>  
  <div class="row">
    <div class="card col s12 m6 z-depth-1"> 
      <div class="card-title">
        <div class="card-content">
          <h5 class="yellow-text text-darken-3 center-align">Course Details</h5>  
          <div class="divider"></div>
          <div class="aejay">
            <p><b class="blue-text text-darken-4">Date:</b> <?php echo date("l, jS F, Y", strtotime($dated)) ?></p>
            <p><b class="blue-text text-darken-4">Venue:</b> <?php echo $course['venue'] ?></p>
          </div>
        </div>
        <div class="row">
          <dl>
            <dt class="red-text">How To Check In as Present</dt>
            <dd> Enter your Email Address </dd>
            <dd> Enter your Password </dd>
            <dd> Click Check In </dd>
          </dl>  
        </div>
      </div>  
    </div>

    <div class="col s12 m6 right"> 
      <nav class="card row yellow darken-2">
        <div class="nav-wrapper col s10">
          <p class="card-title flow-text center-align black-text" style="margin: 0px">Attendance Form</p>
        </div>
        <div class="col s2 center-align z-depth-1">
          <a href=""><i class="material-icons">refresh</i></a>
        </div>
      </nav>  

      <div class="card z-depth-3 white">
        <div class="card-content" id="registry">
          <form data-dest="<?php echo __url__.'/actions/ldc.actions.php/' ?>" data-output="#registry" class="form" form-type="form">
            <datalist id="emails">
              <?php if ($all !== false) {
                foreach ($all as $al) { ?>
                  <option value="<?= $al['pmail'] ?>">
              <?php } } ?>  
            </datalist>
            <div class="input-field">
              <input type="text" id="username" name="username" required class="validate" list="emails">
              <label for="username">Email / Phone</label>
            </div>
            <div class="input-field">
              <input type="password" id="passphrase" name="passphrase" required class="validate">
              <label for="passphrase">Password</label>
            </div>
            <div class="input-field center-align">
              <input type="hidden" name="registry" value="<?= $value ?>">
              <input type="submit" value="CHECK-IN" class="blue darken-4  white-text btn large">
            </div>
          </form>
        </div>  
      </div> 
    </div>    
  </div>  
</section>
