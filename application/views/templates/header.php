<!-- BEGIN BODY -->
<?php $is_mini = isset($_COOKIE['main_menu'])&&$_COOKIE['main_menu']=="mini"; ?>
<?php $pull_left = lang("is_rtl")?"pull-right":"pull-left"; ?>
<?php $pull_right = lang("is_rtl")?"pull-left":"pull-right"; ?>
<body class="" <?php echo lang("is_rtl")?"dir='rtl'":"" ?>>
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse ">
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
    <div class="header-seperation pull-left flip" style="<?php echo $is_mini?"display:none;":"" ?>">
      <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
        <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu" class="">
          <div class="iconset top-menu-toggle-white"></div>
          </a> </li>
      </ul>
      <!-- BEGIN LOGO -->
      <a href="<?=site_url("/") ?>"><h3 style="font-weight: 100"><?php echo lang("site_title") ?></h3></a>
      <!-- END LOGO -->
    </div>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <div class="header-quick-nav">
      <!-- BEGIN TOP NAVIGATION MENU -->
      <div class="pull-left flip">
        <ul class="nav quick-section">
          <li class="quicklinks"> 
            <a href="#" class="" id="layout-condensed-toggle">
              <div class="iconset top-menu-toggle-dark"></div>
            </a> 
          </li>
        </ul>
      </div>
      <!-- END TOP NAVIGATION MENU -->
      <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-left flip"> 
        <div class="progress-radio">
          <div class="progress no-animate transparent progress-success progress-small no-radius no-margin">
            <div style="width: 0%;" class="bar  no-animate"></div>
          </div>
          <div class="details"></div>                        
        </div>
        <div class="system-date">
          <h3><?php echo date("H:i"); ?><small><?php echo date("d/m/Y"); ?></small></h3>
        </div>
        <!-- <div class="app pull-right">
          <a href="<?php echo base_url("Radio-Almarfah.apk") ?>">
            <img src="<?php echo base_url("assets/img/download_android.png") ?>" class="tip" style="height:46px;margin-top:7px;" alt="Download App for Android" title="Download App for Android" data-placement="bottom">
          </a>
        </div> -->
      </div>

      <div class="pull-right flip">
        <div class="chat-toggler" style="min-width: inherit;">
          <a>
            <div class="user-details"> 
            <div class="username">
              <?php echo $this->session->userdata('first_name') ?><span class="bold"><?php echo $this->session->userdata('last_name') ?></span>                  
            </div>            
          </div>
          </a>
        </div>
        <ul class="nav quick-section ">
          <li class="quicklinks">
            <a data-toggle="dropdown" class="dropdown-toggle  <?php echo $pull_right ?>" href="#">
              <div class="iconset top-settings-dark "></div>
            </a>
            <ul class="dropdown-menu  <?php echo $pull_right ?>" role="menu" aria-labelledby="dropdownMenu">
              <li>
                <a href="<?php echo site_url('/auth/change_password') ?>"><i class="icon-lock"></i>&nbsp; <?php echo lang("change_password") ?></a>
              </li>
              <li class="divider"></li>
              <li><a href="<?php echo site_url('/auth/logout') ?>"><i class="icon-off"></i>&nbsp; <?php echo lang("logout") ?></a></li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- END CHAT TOGGLER -->
    </div>
    <!-- END TOP NAVIGATION MENU -->
  </div>
  <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<?php 
  $module = $this->router->fetch_class();
  $method = $this->router->fetch_method();
?>
<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
  <!-- BEGIN SIDEBAR -->
  <div class="page-sidebar pull-left flip  <?php echo $is_mini?"mini":"" ?>" id="main-menu">
    <!-- BEGIN SIDEBAR MENU -->
    <p class="menu-title"><?php echo lang("first_configurations") ?></p>
    <ul>
      <li class="<?php echo $module=="track"?"active":"" ?>" title="<?php echo lang("tracks") ?>"> 
        <a href="<?=site_url("/track") ?>"> <i class="icon-music"></i> <span class="title"><?php echo lang("tracks") ?></span></a> 
      </li>
      <li class="<?php echo $module=="playlist"?"active":"" ?>" title="<?php echo lang("playlists") ?>"> 
        <a href="<?=site_url("/playlist") ?>"> <i class="icon-tasks"></i> <span class="title"><?php echo lang("playlists") ?></span></a> 
      </li>
      <li class="<?php echo $module=="calendar"?"active":"" ?>" title="<?php echo lang("calendar") ?>"> 
        <a href="<?=site_url("/calendar") ?>"> <i class="icon-calendar"></i> <span class="title"><?php echo lang("calendar") ?></span></a> 
      </li>
      <li class="<?php echo $module=="stream"&&$method=="week_calendar"?"active":"" ?>" title="<?php echo lang("week_calendar") ?>"> 
        <a href=""> 
          <i class="icon-calendar-empty"></i> 
          <span class="title"><?php echo lang("widgets") ?></span>
          <span class="arrow"></span>
        </a> 
        <ul class="sub-menu" style="display: none;">
          <li class="<?php echo $module=="stream"&&$method=="player"?"active":"" ?>"> 
            <a href="<?=site_url("/stream/player") ?>"><i class="icon-bolt"></i> <?php echo lang("live") ?></a> 
          </li>
          <li class="<?php echo $module=="stream"&&$method=="week_calendar"&&in_array("events", $this->uri->segments)?"active":"" ?>"> 
            <a href="<?=site_url("/stream/week_calendar/events") ?>"> <i class="icon-calendar"></i> <?php echo lang("week_calendar_by_events") ?></a> 
          </li>
          <li class="<?php echo $module=="stream"&&$method=="week_calendar"&&in_array("tracks", $this->uri->segments)?"active":"" ?>"> 
            <a href="<?=site_url("/stream/week_calendar/tracks") ?>"> <i class="icon-calendar"></i> <?php echo lang("week_calendar_by_tracks") ?></a> 
          </li>
        </ul>
      </li>
      <li class="<?php echo $module=="settings"||$module=="auth"?"active":"" ?>" title="<?php echo lang("configurations") ?>"> 
        <a href=""> 
          <i class="icon-gears"></i> 
          <span class="title"><?php echo lang("configurations") ?></span>
          <span class="arrow"></span>
        </a> 
        <ul class="sub-menu" style="display: none;">
          <li class="<?php echo $module=="settings"&&$method=="index"?"active":"" ?>"> 
            <a href="<?=site_url("/settings") ?>"> <i class="icon-gear"></i> <?php echo lang("settings") ?></a> 
          </li>
          <li class="<?php echo $module=="settings"&&$method=="sys_info"?"active":"" ?>"> 
            <a href="<?=site_url("/settings/sys_info") ?>"> <i class="icon-info"></i> <?php echo lang("system_info") ?></a> 
          </li>
          <li class="<?php echo $module=="auth"&&$method=="index"?"active":"" ?>"> 
            <a href="<?=site_url("/auth/index") ?>"> <i class="icon-user"></i> <?php echo lang("users") ?></a> 
          </li>
        </ul>
      </li>
    </ul>
    <a href="#" class="scrollup <?php echo $is_mini?"to-edge":"" ?>">Scroll</a>
    <div class="clearfix"></div>
    <!-- END SIDEBAR MENU -->
  </div>
  <!-- END SIDEBAR -->
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content <?php echo $is_mini?"condensed":"" ?>"> 
    <div class="clearfix"></div>
    <div class="content">  