<!DOCTYPE html>
<?php require_once dirname(__FILE__).'/../config/config.php'; ?>
<?php require_once $servicePath.'/lib/authentication/Authenticator.php'; ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>Error Actual Urls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />

<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo $staticPath;?>/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo $staticPath;?>/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo $staticPath;?>/plugins/jquery-datatable/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticPath;?>/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<!-- END PLUGIN CSS -->
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?php echo $staticPath;?>/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticPath;?>/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticPath;?>/plugins/font-awesome/css/font-awesome_v4.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticPath;?>/css/animate.min.css" rel="stylesheet" type="text/css" />
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?php echo $staticPath;?>/css/style_v2.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticPath;?>/css/responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticPath;?>/css/custom-icon-set.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $staticPath;?>/css/mis_v1.css" rel="stylesheet" type="text/css" />
<script>
	var serviceBaseUrl = "<?php echo $serviceBaseUrl;?>";
</script>
<!-- END CSS TEMPLATE -->

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse ">
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
    <div class="header-seperation">
      <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
        <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu" class="">
          <div class="iconset top-menu-toggle-white"></div>
          </a> </li>
      </ul>
      <!-- BEGIN LOGO -->
      <a class="dashName" href="#"><img src="<?php echo $staticPath;?>/i/newmonk.png" alt=""/></a>
      <!-- END LOGO -->
      <ul class="nav pull-right notifcation-center">
        <li class="dropdown" id="header_task_bar"> <a href="#" class="dropdown-toggle active" data-toggle="">
          <div class="iconset top-home"></div>
          </a> </li>
      </ul>
    </div>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <div class="header-quick-nav">
      <!-- BEGIN TOP NAVIGATION MENU -->
      <div class="pull-left">
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="#" class="" id="layout-condensed-toggle">
            <div class="iconset top-menu-toggle-dark"></div>
            </a> </li>
        </ul>
      </div>
      <!-- END TOP NAVIGATION MENU -->
      <a class="dashName" href="#" id="logo-center"><img src="<?php echo $staticPath;?>/i/newmonk.png" alt="">  </a>
      <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-right">

        <ul class="nav quick-section ">
          <li class="quicklinks"> <a data-toggle="dropdown" class="dropdown-toggle  pull-right" href="#">
            <div class="iconset top-settings-dark "></div>
            </a>
            <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="dropdownMenu">
              <li><a href="login.php"><i class="icon-off"></i>&nbsp;&nbsp;Log Out</a></li>
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
<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
  <!-- BEGIN SIDEBAR -->
  <div class="page-sidebar" id="main-menu">
    <!-- BEGIN SIDEBAR MENU -->
    <a href="#" class="scrollup">Scroll</a>
    <div class="clearfix"></div>
    <!-- END SIDEBAR MENU -->
  </div>
  <!-- END SIDEBAR -->
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div id="portlet-config" class="modal hide">
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"></button>
        <h3>Widget Settings</h3>
      </div>
      <div class="modal-body"> Widget settings form goes here </div>
    </div>
    <div class="clearfix"></div>
    <div class="content">
      <div class="span8">
        <ul class="breadcrumb">
          <li>
            <p>YOU ARE HERE</p>
          </li>
	<i class="icon-angle-right"></i>
    <li><a href="misAppSelect.php" class="appName active">Select App</a> </li>
    <i class="icon-angle-right"></i>
    <li><a href="misDashboard.php" class="active">DashBoard</a> </li>
    <i class="icon-angle-right"></i>
    <li><a href="#" class="active">nLogger</a></li>
          </div>
      <div class="row-fluid">
        <div class="span12">
          <div class="grid simple ">
            <div class="grid-title">
              <div class="row-fluid">
                <h4 class="span6">Detailed <span class="semi-bold"> View</span></h4>
                 </div>
            </div>


            <div class="grid-body ">


              <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover no-more-tables" id="pageListTable" width="100%">
                   </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- END PAGE -->
<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?php echo $staticPath;?>/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>

<script src="<?php echo $staticPath;?>/j/plugins.min.js" type="text/javascript"></script>


<!--<script src="<?php echo $staticPath;?>/js/datatables_nlogger.js" type="text/javascript"></script>-->
<script src="<?php echo $staticPath;?>/js/datatables_nlogger_v5.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/js/form_elements_v6.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/js/getjson_v1.js"></script>
<script src="<?php echo $staticPath;?>/js/utility_v2.js"></script>

<script src="<?php echo $staticPath;?>/js/callsList_nlog_v4.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/js/breadcrumb_v3.js"></script>
<script src="<?php echo $staticPath;?>/js/init_nlogger.js"></script>
<!-- BEGIN CORE TEMPLATE JS -->
<script src="<?php echo $staticPath;?>/js/core_v2.js" type="text/javascript"></script>
<!-- END CORE TEMPLATE JS -->
<!-- END JAVASCRIPTS -->
</body>
