<?php 
  if(!isset($_SESSION['aj.gaclintra']['user'])){
    header("Location: ".__url__."/login/");
  }
?>
<header>
  <ul id="slide-out" class="side-nav side fixed white" style="margin-top: 0px">
    <div class="container-fluid" style="margin: 0px">
      <li class="logo">
          <img src="<?= __assets__.'/img/gacl-logo.jpg' ?>" alt="GACL" class=" responsive-img img-thumbnail" >
      </li>
    </div>
    <li><a class="waves-effect" href="<?= __url__.'/home/' ?>"><i class="material-icons left purple-text">dashboard</i> <span class="topview"> Home </span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/directory/' ?>"><i class="material-icons left red-text">recent_actors</i> <span class="topview"> Directory </span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/media/' ?>"><i class="material-icons left green-text">perm_media</i> <span class="topview"> Media Room </span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/info/' ?>"><i class="material-icons left blue-text">cast</i> <span class="topview"> News / Information </span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/docs/' ?>"><i class="material-icons left red-text text-darken-2">attach_file</i> <span class="topview"> Shared Documents </span></a></li> 
    <li><a class="waves-effect" href="<?= __url__.'/corporate/' ?>"><i class="material-icons left orange-text">verified_user</i> <span class="topview"> Corporate Profile </span></a></li>       
    <li><a class="waves-effect hide" href="<?= __url__.'/calendar/' ?>"><i class="material-icons left green-text">event</i> <span class="topview"> Calendar </span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/forum/' ?>"><i class="material-icons left brown-text">forum</i> <span class="topview"> General Discussions </span></a></li>
    <li><a class="waves-effect spec-ajax" href="#" data-query="apps" data-dest="<?php echo __url__.'/actions/home.actions.php' ?>" data-output=".modal-content" data-toggle="modal" ><i class="material-icons left red-text">apps</i> <span class="topview"> Applications </span></a></li>
    <li><a class="waves-effect" href="http://10.112.1.197/helpdesk/" target="_blank"><i class="material-icons left green-text text-darken-4">record_voice_over</i> <span class="topview"> ICT Help Desk </span></a></li>  
    <li><a class="waves-effect" href="http://41.191.98.242/" target="_blank"><i class="material-icons left orange-text">help_outline</i> <span class="topview"> Support Center (FMCC) </span></a></li> 
    <div class="footer center">
      <p class="grey-text">GACL  ICT &copy <?= $copyright ?></p>
    </div>    
  </ul>
  <nav class="top-nav">
    <div class="nav-wrapper green z-depth-2">
      <a href="#!" class="brand-logo hide-on-med-and-down" style="padding-left: 30%; padding-right: 20%"></a>

      <ul class="right admin-menu-nav">
        <li><a href="http://www.gacl.com.gh" target="_blank"><i class="material-icons left">language</i><span class="hide-on-small-only">Web</span></a> </li>
        <li><a href="https://mail.gacl.com.gh" target="_blank"><i class="material-icons left">mail</i><span class="hide-on-small-only right">Mail</span></a> </li>
        <li><a href="http://10.112.1.31:9001/forms/frmservlet?" target="_blank"><i class="material-icons left">phonelink</i><span class="hide-on-small-only right">ERP</span></a> </li>
        <li class="">
          <a href="#!" data-activates='dropdown1' class="waves-effect dropdown-button user-email">
            <?= $_SESSION['aj.gaclintra']['user'] ?><i class="material-icons right" style="margin-left: 0px !important">arrow_drop_down</i>
          </a>
        <!-- Dropdown Structure -->
          <ul id='dropdown1' class='dropdown-content'>
            <li class="<?= ($_SESSION['aj.gaclintra']['role']=='EDIT' || $_SESSION['aj.gaclintra']['role']=='RES')? 'hide' : '' ?>"><a href="<?= __url__.'/profile/' ?>" class="waves-effect admin-menu">Account</a></li>
            <li class="<?= ($_SESSION['aj.gaclintra']['role']=='NOR' || $_SESSION['aj.gaclintra']['role']=='RES' || $_SESSION['aj.gaclintra']['role']=='SEC')? 'hide' : '' ?>"><a href="<?= __url__.'/siteUpdate/' ?>" class="waves-effect admin-menu">Updates</a></li>
            <li class="divider"></li>
            <li><a href="#" class="waves-effect spec-ajax" data-query="logout" data-dest="<?php echo __url__.'/actions/login.actions.php' ?>" data-output=".modal-content">Logout</a></li>
          </ul>
      </li>
      </ul>
      <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
</header>
