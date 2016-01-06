<!DOCTYPE html>
<?php require_once dirname(__FILE__).'/../config/config.php'; ?>
<?php require_once $servicePath.'/lib/authentication/Authenticator.php'; ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>Error Urls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />

<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo $staticPath;?>/c/plugins.min.css" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?php echo $staticPath;?>/c/core.min.css" rel="stylesheet" type="text/css" />
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?php echo $staticPath;?>/c/brand.min.css" rel="stylesheet" type="text/css" />
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
      <a class="dashName" href="#"><img src="<?php echo $staticPath;?>/i/newmonk.png" alt=""/>  </a>
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
              <li><a href="<?php echo $serviceBaseUrl; ?>/logout.php"><i class="icon-off"></i>&nbsp;&nbsp;Log Out</a></li>
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
   <!-- <ul>
      <li class=""> <a href="javascript:;" class="redirect"><i class="icon-rocket"></i><span class="title"> Boomerang</span> <span class="arrow "></span> </a>

      </li>
      <li class=""> <a href="javascript:;"> <i class="icon-bug"></i> <span class="title">nLogger</span><span class="arrow "></span></a></li>
      <li class=""> <a href="javascript:;"> <i class="icon-globe"></i><span class="title">Webpage Test API</span> <span class="arrow "></span> </a>
        <ul class="sub-menu">
          <li> <a href="tables.html"><i class="icon-bar-chart"></i> Analytical Review</a> </li>
        </ul>
      </li>
      <li class=""> <a href="javascript:;"> <i class="icon-mobile-phone"></i><span class="title">WURFL</span> </a></li>
    </ul>-->
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
                <h4 class="span3">Detailed <span class="semi-bold"> View</span></h4>
                <div class="span4" id="error-msg">
                </div>
                <div class="span5 pull-right">

                 <div class="tools pull-right"> <a class="reload MISreload" href="javascript:;" rel='loadStatesData'></a> </div>
                  <div class="pull-right MIScal">
                      <small>From :</small> <span class="input-append success date">
                              <input id="startDate" type="text" class="span12 no-boarder datepicker" />
                              <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span>
                            </span>  <small>To :</small> <span class="input-append success date">
                              <input id="endDate" type="text" class="span12 no-boarder datepicker" />
                              <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span> </span>
<a id="changeDate" href="javascript:;" class="btn btn-white gobtn">GO</a>
                  </div>
                </div>
                 </div>
            </div>


            <div class="grid-body ">


              <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover no-more-tables" id="nLoggerTable" width="100%">
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="admin-bar" id="quick-access" style="display:">
      <div class="admin-bar-inner nLogger">
      <a class="btn btn-link fr" id="all-result">Remove Filters</a>

      <form class="row-fluid">
        <div class="form-horizontal pull-left span9">
    		<select class="selectpicker" multiple data-selected-text-format="count>10" title='File' id="select-file"></select>
            <select class="selectpicker" multiple data-selected-text-format="count>10" title='Error' id="select-error"></select>
  			<select class="selectpicker" multiple data-selected-text-format="count>10" title='Url' id="select-url"></select>
            <select class="selectpicker" multiple data-selected-text-format="count>10" title='Browser' id="select-browser"></select>
  			<select class="selectpicker" multiple data-selected-text-format="count>10" title='Screen Resolution' id="select-res"></select>
  			<select class="selectpicker" multiple data-selected-text-format="count>10" title='OS' id="select-os"></select>
       </div>
      <div class="pull-right span2 btn-filter">
        <button class="btn btn-primary btn-cons btn-add" type="button" id="filterSearch">Search </button>
        <button class="btn btn-white btn-cons btn-cancel" type="button">Cancel</button>
      </div>
     </form>

      </div>
    </div>
  </div>
</div>
<!-- END PAGE -->
<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?php echo $staticPath;?>/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>

<script src="<?php echo $staticPath;?>/j/plugins.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/j/jCommonPlugs.min.js"></script>
<script src="<?php echo $staticPath;?>/j/errorPage.min.js" type="text/javascript"></script>

<!-- BEGIN CORE TEMPLATE JS -->
<!-- END CORE TEMPLATE JS -->
<!-- END JAVASCRIPTS -->
</body>
