<?php

  // initialise controller class
  $class = new Sec;

  $docs = $class->index($_SESSION['aj.gaclintra']['dept']);   
  $stats = $class->statistics(date('Y'),$_SESSION['aj.gaclintra']['dept']);
?> 
<header>
   <nav class="container-fluid">
    <div class="nav-wrapper green darken-5 cursor">
      <ul>
        <li class="center flow-text" style="padding-left: 5px"><i class="material-icons left">library_books</i> <span class="bold hide-on-small-and-down">GACL CORRESPONDENCE MODULE</span>
        </li>
        <li class="right flow-text cursor"><a href="#1" data-activates='dropdown1' class="flow-text waves-effect dropdown-button yellow-text text-darken-2"><?= $_SESSION['aj.gaclintra']['user'] ?><i class="material-icons right" style="margin-left: 0px !important">arrow_drop_down</i></a>
          <ul id='dropdown1' class='dropdown-content'>
            <li><a href="" class="spec-ajax" data-extend-view=".pass_form" data-parent=".nav-wrapper" data-output=".modal-content" data-toggle="modal" return="true">Change Password</a></li>
            <li><a href="" class="spec-ajax" data-extend-view=".stats_form" data-parent=".nav-wrapper" data-output=".modal-content" data-toggle="modal" return="true">Statistics</a></li>
            <li><a href="" class="waves-effect spec-ajax" data-query="logout" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content">Logout</a></li>
          </ul>        
        </li>           
      </ul>

      <div class="pass_form hide">
        <div class="row">
          <div class="col s12">
            <form class="form" data-dest="<?= __url__.'/actions/account.actions.php' ?>" data-output=".modal-content" form-type="form">
              <div class="input-field">
                <input type="password" id="password" name="password" required="true">
                <label for="password">Current Password</label>
              </div>
              <div class="input-field">
                <input type="password" id="npass" name="npass" required="true">
                <label for="password">New Password</label>
              </div>
              <div class="input-field">
                <input type="password" id="rpass" name="rpass" required="true">
                <label for="password">Retype Password</label>
              </div>
              <div class="input-field center-align">
                <input type="hidden" name="pass" value="set">
                <input type="submit" value="CHANGE" class="btn indigo">
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="stats_form hide">
        <div class="card-title" style="padding-top: 5px">
          <h4 class="center bold red-text text-darken-2">Document Statistics</h4>
        </div>
        <div class="divider"></div>
        <div class="card-content">
          <ul class="flow-text">
            <li>Total Documents Processed:  <b class="blue-text text-darken-4"><?= $stats[0] ?></b></li><br>
            <li style="padding-left: 2em">Total Documents Received: <b class="blue-text text-darken-4"><?= $stats[1] ?></b></li>
            <li style="padding-left: 2em">Total Documents Dispatched: <b class="blue-text text-darken-4"><?= $stats[2] ?></b></li><br>
            <li style="padding-left: 2em">Total Documents External: <b class="blue-text text-darken-4"><?= $stats[3] ?></b></li>
            <li style="padding-left: 2em">Total Documents Internal: <b class="blue-text text-darken-4"><?= $stats[4] ?></b></li>
          </ul>
        </div>
      </div>
    </div>

  </nav> 

  <nav class="container-fluid" style="background: transparent !important;">
    <div class="nav-wrapper" id="lowbar">
      <ul>
        <li class="center black-text" style="padding: 10px 0px 0px 20px"> 
          <select class="browser-default dynamic-search" id="dispatch-type" name="dispatch-type" data-output=".responsive-table" data-query="dispatch-type" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>">
            <option disabled>Please Select Document Module: </option>
            <option selected value="3">Document Receipt</option>
            <option value="2">Internal Dispatch</option>
            <option value="1">Intra-Dept Dispatch</option>
          </select>
        </li>
        <li><a href="" class="spec-ajax black-text" data-extend-view=".search_form" data-parent="#lowbar" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">search</i></a></li>
        <li class="center hide" style="width: 50%"><input id="search" type="search" required placeholder="Search by Subject or Document Code" class="green darken-2 center dynamic-search black-text" data-query="search" data-dest="<?php echo __url__.'/actions/sec.actions.php'; ?>" data-output="tbody">
        </li>
        <li class="right">
          <a href="#" class="waves-effect spec-ajax green-text" data-query="ext-generate" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-toggle="modal"><i class="material-icons left">edit</i> <span class="hide-on-small-and-down">Record External</span> </a>
        </li>
        <li class="right">
          <a href="#" class="waves-effect spec-ajax brown-text" data-query="int-generate" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" data-toggle="modal"><i class="material-icons left">add</i> <span class="hide-on-small-and-down">Create New</span> </a>
        </li>
      </ul>
      <div class="search_form hide">
        <div class="row">
          <div class="col s12">
            <form class="form" data-dest="<?= __url__.'/actions/sec.actions.php' ?>" data-output=".modal-content" form-type="form">
              <div class="input-field">
                <input type="text" id="docsubject" name="docsubject">
                <label for="docsubject">Subject Matter or Document Code</label>
              </div>
              <div class="input-field">
                <select class="browser-default dynamic-search" id="doccriteria" name="doccriteria" data-output=".criteria" data-query="doccriteria" data-dest="<?php echo __url__.'/actions/sec.actions.php' ?>" required>
                  <option disabled>Select Document Category</option>
                  <option value="9" selected>All Categories</option>
                  <option value="3">External Document</option>
                  <option value="2">Inter Department Dispatch</option>
                  <option value="1">Internal Dispatch</option>
                </select>
              </div>
              <div class="criteria input-field">
              </div>
              <div class="input-field center-align">
                <input type="hidden" name="docsearch" value="set">
                <input type="submit" value="SEARCH" class="btn red">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>  
  </nav>
</header>

<div class="row">
  <table class="responsive-table">
    <?php include("./external.php") ?>
  </table>
</div>