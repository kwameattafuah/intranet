<?php
  if(!isset($_SESSION['aj.gaclintra']['user'])){
    header("Location: ".__url__."/login/");
    exit;
  }
?>
<header>
  <!-- ── Sidebar ───────────────────────────────────────────── -->
  <ul id="slide-out" class="side-nav side fixed">

    <div class="sidebar-logo">
      <img src="<?= __assets__.'/img/gacl-logo.jpg' ?>" alt="GACL" class="responsive-img">
      <div class="sidebar-org-name">Ghana Airports Company Ltd</div>
    </div>

    <!-- Main -->
    <span class="nav-section-label">Main</span>
    <li><a class="waves-effect" href="<?= __url__.'/home/' ?>">
      <i class="material-icons left">dashboard</i><span class="topview">Home</span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/directory/' ?>">
      <i class="material-icons left">recent_actors</i><span class="topview">Staff Directory</span></a></li>

    <!-- Communication -->
    <hr class="sidebar-divider">
    <span class="nav-section-label">Communication</span>
    <li><a class="waves-effect" href="<?= __url__.'/info/' ?>">
      <i class="material-icons left">cast</i><span class="topview">News &amp; Information</span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/forum/' ?>">
      <i class="material-icons left">forum</i><span class="topview">General Discussions</span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/media/' ?>">
      <i class="material-icons left">perm_media</i><span class="topview">Media Room</span></a></li>

    <!-- Resources -->
    <hr class="sidebar-divider">
    <span class="nav-section-label">Resources</span>
    <li><a class="waves-effect" href="<?= __url__.'/docs/' ?>">
      <i class="material-icons left">attach_file</i><span class="topview">Shared Documents</span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/corporate/' ?>">
      <i class="material-icons left">verified_user</i><span class="topview">Corporate Profile</span></a></li>
    <li><a class="waves-effect spec-ajax" href="#" data-query="apps" data-dest="<?= __url__.'/actions/home.actions.php' ?>" data-output=".modal-content" data-toggle="modal">
      <i class="material-icons left">apps</i><span class="topview">Applications</span></a></li>

    <!-- Learning & Development -->
    <hr class="sidebar-divider">
    <span class="nav-section-label">Learning &amp; Development</span>
    <li><a class="waves-effect" href="<?= __url__.'/ldc/' ?>">
      <i class="material-icons left">school</i><span class="topview">L&amp;D Centre</span></a></li>
    <li><a class="waves-effect" href="<?= __url__.'/ldc/rooms/' ?>">
      <i class="material-icons left">meeting_room</i><span class="topview">Room Booking</span></a></li>
    <?php if(isset($_SESSION['aj.gaclintra']['role']) && in_array($_SESSION['aj.gaclintra']['role'], ['ADMIN','SUPER','LDC'])): ?>
    <li><a class="waves-effect" href="<?= __url__.'/ldc/admin/' ?>">
      <i class="material-icons left">settings</i><span class="topview">L&amp;D Admin</span></a></li>
    <?php endif; ?>

    <!-- Support -->
    <hr class="sidebar-divider">
    <span class="nav-section-label">Support</span>
    <li><a class="waves-effect" href="http://10.112.1.197/helpdesk/" target="_blank">
      <i class="material-icons left">record_voice_over</i><span class="topview">ICT Help Desk</span></a></li>
    <li><a class="waves-effect" href="http://41.191.98.242/" target="_blank">
      <i class="material-icons left">help_outline</i><span class="topview">Support Centre (FMCC)</span></a></li>

    <div class="sidebar-footer">
      &copy; <?= $copyright ?> <?= ORG_SHORT ?> ICT
    </div>
  </ul>

  <!-- ── Top Bar ───────────────────────────────────────────── -->
  <nav class="top-nav">
    <div class="nav-wrapper">
      <a href="#!" class="brand-logo hide-on-med-and-down"></a>

      <ul class="right admin-menu-nav" style="display:flex; align-items:center;">
        <li><a href="http://www.gacl.com.gh" target="_blank" title="GACL Website">
          <i class="material-icons left">language</i><span class="hide-on-small-only">Web</span></a></li>
        <li><a href="https://mail.gacl.com.gh" target="_blank" title="GACL Mail">
          <i class="material-icons left">mail</i><span class="hide-on-small-only">Mail</span></a></li>
        <li><a href="http://10.112.1.31:9001/forms/frmservlet?" target="_blank" title="ERP System">
          <i class="material-icons left">phonelink</i><span class="hide-on-small-only">ERP</span></a></li>

        <li>
          <a href="#!" data-activates="dropdown1" class="waves-effect dropdown-button user-email">
            <i class="material-icons left" style="font-size:18px">account_circle</i>
            <?= htmlspecialchars($_SESSION['aj.gaclintra']['user']) ?>
            <i class="material-icons right" style="margin-left:0">arrow_drop_down</i>
          </a>
          <ul id="dropdown1" class="dropdown-content">
            <li class="<?= (in_array($_SESSION['aj.gaclintra']['role'] ?? '', ['EDIT','RES'])) ? 'hide' : '' ?>">
              <a href="<?= __url__.'/profile/' ?>" class="waves-effect admin-menu">
                <i class="material-icons left tiny">person</i> My Account
              </a>
            </li>
            <li class="<?= (in_array($_SESSION['aj.gaclintra']['role'] ?? '', ['NOR','RES','SEC'])) ? 'hide' : '' ?>">
              <a href="<?= __url__.'/siteUpdate/' ?>" class="waves-effect admin-menu">
                <i class="material-icons left tiny">edit</i> Site Updates
              </a>
            </li>
            <li class="divider"></li>
            <li>
              <a href="#" class="waves-effect spec-ajax" data-query="logout"
                 data-dest="<?= __url__.'/actions/login.actions.php' ?>"
                 data-output=".modal-content">
                <i class="material-icons left tiny">exit_to_app</i> Logout
              </a>
            </li>
          </ul>
        </li>
      </ul>

      <a href="#" data-activates="slide-out" class="button-collapse">
        <i class="material-icons">menu</i>
      </a>
    </div>
  </nav>
</header>
