<?php 
  if(!isset($_SESSION['aj.ldc']['ldcuser'])){
    header("Location: ".__url__.'/ldc/');
  }

  date_default_timezone_set("Africa/Accra");
  $copyright = date("Y"); 
?>
<header>
  <ul id="slide-out" class="side-nav side-menu-nav fixed blue darken-4" style="margin-top: 0px; overflow-y: hidden;">
    <div class="container-fluid" style="margin: 0px">
      <li class="logo">
          <img src="<?= __assets__.'/img/gacl-logo.jpg' ?>" alt="" class=" responsive-img img-thumbnail" >
      </li>
    </div>
    <li class="center cursor"><h6 class="grey-text">Auditorium Booking</h6></li> 
    <li><a href="" data-dest="<?= __url__.'/views/ldc/admin/bookboard.php' ?>" class="side-menu spec-ajax white-text waves-effect" data-output="#main">Dashboard</a></li>
    <li><a href="" data-dest="<?= __url__.'/views/ldc/admin/boffice.php' ?>" class="side-menu spec-ajax white-text waves-effect" data-output="#main">Back Office</a></li>     
    <li><a href="" data-dest="<?= __url__.'/views/ldc/admin/rooms.php' ?>" class="side-menu spec-ajax white-text waves-effect" data-output="#main">LDC Rooms</a></li> 
    <li><p class="center"><a href="" class="green-text">GACL ICT <span class="black-text">&copy <?= $copyright ?> </span></a></p></li>  
  </ul>
  <nav class="top-nav">
    <div class="nav-wrapper green z-depth-2">
      <a href="#!" class="brand-logo hide-on-med-and-down" style="padding-left: 30%; padding-right: 20%"></a>

      <ul class="right  admin-menu-nav">
        <li class=""><i class="hide fa fa-user"></i><a href="#!" data-activates='dropdown1' class="waves-effect dropdown-button user-email"><?= $_SESSION['aj.ldc']['ldcuser'] ?> <i class="material-icons right">arrow_drop_down</i></a>
        <!-- Dropdown Structure -->
          <ul id='dropdown1' class='dropdown-content'>
            <li><a href="" data-query="account" data-dest="<?= __url__.'/ldc/adminprofile/' ?>" class="admin-menu waves-effect spec-ajax" data-output="#main">Account</a></li>
            <li><a href="" data-dest="<?= __url__.'/views/ldc/admin/admins.php' ?>" class="admin-menu spec-ajax waves-effect" data-output="#main">Admins</a></li>
            <li><a href="<?= __url__.'/ldc/admin/?ldcquest=lta' ?>" class="admin-menu waves-effect">Switch: LDC Training</a></li>
            <li class="divider"></li>
            <li><a href="#" class="waves-effect spec-ajax" data-query="logout" data-dest="<?php echo __url__.'/actions/ldclogin.actions.php' ?>" data-output=".modal-content">Logout</a></li>
          </ul>
      </li>
      </ul>
      <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
</header>

<div id="main" class="row">
  <main>
    <div class="card">
    <div class="card-image waves-effect waves-block waves-light">
      <img class="activator" src="<?= __assets__.'/img/gacl-logo.jpg' ?>">
    </div>
    <div class="card-content">
      <span class="card-title activator grey-text text-darken-4">LDC Admin Panel Help Info<i class="material-icons right">more_vert</i></span>
      <p class="grey-text">GACL  ICT &copy <?= $copyright ?> </p>
    </div>
    <div class="card-reveal">
      <span class="card-title grey-text text-darken-4">LDC Admin Panel<i class="material-icons right">close</i></span>
      <p>A section of the Human Capital and Office Services that handles and monitors Human Capital Development.</p>
    </div>
  </div> 
  </main>
</div>

