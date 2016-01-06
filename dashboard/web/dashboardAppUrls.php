<!DOCTYPE html>
<?php //require_once dirname(__FILE__).'/../config/config.php'; ?>
<?php //require_once $servicePath.'/lib/Authenticator.php'; ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<script>
var serviceBaseUrl = "<?php echo $serviceBaseUrl;?>";
var errorBaseUrl = "<?php echo $errorBaseUrl;?>";
</script>
<title>Performance Urls</title>
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
<!-- END CSS TEMPLATE -->
<style>
.appCrashContainer{margin-left:0;}
</style>
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
      <a class="dashName" href="#"><img src="<?php echo $staticPath;?>/i/newmonk.png" alt="">  </a>
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
  
  <!-- END SIDEBAR -->
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content appCrashContainer">
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
            <li><a href="#" class="urlList active">UrlList</a> </li>

        </ul>
      </div>
      <div class="row-fluid">
        <div class="span12 prashu">
          <div class="grid simple" id="urlListGrid">

            <div class="grid-title">
        <div class="row-fluid">
          <h4 class="span6">URL List </h4>
          <div class="span6 pull-right">
                  <div class="tools pull-right"> <a class="reload MISreload" href="javascript:;" rel='urlList'></a> </div>
                  <div class="pull-right MIScal">
                      <small>From :</small> <span class="input-append success date">
                              <input id="startDate" type="text" class="span12 no-boarder" />
                              <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span>
                            </span>  <small>To :</small> <span class="input-append success date">
                              <input id="endDate" type="text" class="span12 no-boarder" />
                              <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span> </span>
<a id="changeDate" href="#" class="btn btn-white gobtn">GO</a>
                  </div>
                </div>
        </div>
      </div>
            <div class="grid-body ">
              <table cellpadding="0" cellspacing="0" border="0" class="table table-hover no-more-tables" width="100%" style="width:100%" id="urlList">
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    
    <div class="row-fluid">
      <!--<div id="loadStatesData" class="grid simple span8">
        <div class="grid-title">
          <h4>Exception <span class="semi-bold">List</span></h4>
          <div class="tools"> <a href="javascript:;" class="reload MISreload" rel='loadStatesData'></a> </div>
        </div>
        <div class="grid-body ">
          <table cellpadding="0" cellspacing="0" border="0" class="table table-hover no-more-tables" width="100%" id="exceptionList">
          </table>
        </div>
      </div>-->
      <!--<div class="span4">
        <div class="grid solid red">
          <div class="grid-title">
            <h4>Url <span class="semi-bold">List</span></h4>
            <div class="tools"> <a class="reload MISreload" href="javascript:;" rel="UrlTimer"></a></div>
          </div>
          <div class="grid-body">
                      <!-- <div class="scroller" data-height="100px"> -->
            <!-- <table cellpadding="0" cellspacing="0" border="0" class="table table-hover no-more-tables" width="100%" id="urlList"> -->
              </table>
            <!-- </div> -->

            <div class="scroller" data-height="300px">
              <table class="table table-hover no-more-tables" id="urlList">
              </table>
            </div>
          </div>
        </div>
      </div>-->
    </div>



  </div>
</div>
<!-- END PAGE -->
<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?php echo $staticPath;?>/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/breakpoints.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
<!-- END CORE JS FRAMEWORK -->
<!-- BEGIN PAGE LEVEL JS -->
<script src="<?php echo $staticPath;?>/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/jquery-slider/jquery.sidr.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/jquery-datatable/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/jquery-datatable/extra/js/TableTools.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/plugins/bootstrap-multiselect/js/bootstrap-select.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $staticPath;?>/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="<?php echo $staticPath;?>/plugins/datatables-responsive/js/lodash.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo $staticPath;?>/js/datatables_nlogger.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/js/form_elements_v6.js" type="text/javascript"></script>
<!-- BEGIN CORE TEMPLATE JS -->
<!-- END CORE TEMPLATE JS -->
<script src="<?php echo $staticPath;?>/js/getjson_v1.js"></script>
<script src="<?php echo $staticPath;?>/js/utility_v2.js"></script>
<script src="<?php echo $staticPath;?>/js/breadcrumb_v3.js"></script>
<script src="<?php echo $staticPath;?>/js/core_v2.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/js/init_toolbars_v2.js"></script>
<script src="<?php echo $staticPath;?>/js/init_urlListAppCrash.js"></script>
<!-- END JAVASCRIPTS -->
<!--<script src="../../../Static1/127/js/init_exceptionList1.js"></script>-->
</body>
