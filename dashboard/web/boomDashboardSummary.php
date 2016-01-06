<!DOCTYPE html>
<?php require_once dirname(__FILE__).'/../config/config.php'; ?>
<?php require_once $servicePath.'/lib/authentication/Authenticator.php'; ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>Performance Summary</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />


<script>
var serviceBaseUrl = "<?php echo $serviceBaseUrl;?>";
var errorBaseUrl = "<?php echo $errorBaseUrl;?>";
</script>
<!-- BEGIN PLUGIN CSS -->
 <link href="<?php echo $staticPath;?>/c/plugins.min.css" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?php echo $staticPath;?>/c/core.min.css" rel="stylesheet" type="text/css" />
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?php echo $staticPath;?>/c/brand.min.css" rel="stylesheet" type="text/css" />
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
      <a class="dashName" href="javascript:;"><img src="<?php echo $staticPath;?>/i/newmonk.png" alt=""/> </a>
      <!-- END LOGO -->
      <ul class="nav pull-right notifcation-center">
        <li class="dropdown" id="header_task_bar"> <a href="javascript:;" class="dropdown-toggle active" data-toggle="">
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
  <ul class="breadcrumb span6">
    <li>
      <p>YOU ARE HERE</p>
    </li>
    <i class="icon-angle-right"></i>
    <li><a href="misAppSelect.php" class="appName active">Select App</a> </li>
    <i class="icon-angle-right"></i>
    <li><a href="misDashboard.php" class="active">DashBoard</a> </li>
    <i class="icon-angle-right"></i>
    <li><a href="boomDashboardUrls.php" class="urlList active">UrlList</a> </li>
   	<i class="icon-angle-right"></i>
    <li><a href="#" class="active">Boomerang</a></li>
    </ul>
  <div class="span6 pull-right">
    <ul class="MISlisting">
      <li id="lt_median"><strong><value></value><span> sec</span></strong><small>Median Load Time</small></li>
      <li id="lt_average"><strong><value></value><span> sec</span></strong><small>Avg. Load Time</small></li>
      <li id="pgv_total"><strong><value><value></strong><small> Page View's</small></li>
    </ul>
  </div>
  <div class="row-fluid">
    <div class="grid simple ">
      <div class="grid-title">
        <div class="row-fluid">
          <h4 class="span6"><span class="semi-bold" id="urlName"></span></h4>
          <div class="span6 pull-right">
                  <div class="tools pull-right"> <a class="reload MISreload" href="javascript:;" rel='loadStatesData'></a> </div>
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
        <div class="row-fluid">
          <div class="span12">
            <div id="container" style="min-width: 310px; height:174px"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row-fluid">
      <div id="loadStatesData" class="grid simple span8">
        <div class="grid-title">
          <h4>Loading <span class="semi-bold">States</span></h4>
          <div class="tools"> <a href="javascript:;" class="reload MISreload" rel='loadStatesData'></a> </div>
        </div>
        <div class="grid-body ">
          <ul class="MISlisting colored">
            <li class="MIScolor1" id="t_resp"><strong><value></value> <span>sec</span></strong><small>First Byte (TTFB)</small></li>
            <li class="MIScolor2" id="t_page"><strong><value></value> <span>sec</span></strong><small>Page Render</small></li>
            <li class="MIScolor3" id="t_domLoaded"><strong><value></value> <span>sec</span></strong><small>DOM Loaded</small></li>
          </ul>
        </div>
      </div>
      <div class="span4">
        <div class="grid solid red">
          <div class="grid-title">
            <h4>Resource <span class="semi-bold">Timing</span></h4>
            <div class="tools"> <a class="reload MISreload" href="javascript:;" rel="resourceTimer"></a></div>
          </div>
          <div class="grid-body">
                      <div class="scroller" data-height="100px">
            <table class="table table-hover no-more-tables" id="resourceTimerTable">
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="grid simple ">
      <div class="grid-title">
        <h4>Load Time Page View<span class="semi-bold"> Graph</span></h4>
        <div class="tools"> <a href="javascript:;" class="reload MISreload" rel="loadTimePageView"></a> </div>
      </div>
      <div class="grid-body">
        <div id="visitor" style="height:250px"></div>
      </div>
      </div>
      </div>
      <div class="row-fluid">
    <div class="grid simple ">

      <div class="grid-title">
        <h4>Detailed Report of<span class="semi-bold"> Visitors</span></h4>
        <div class="tools"> <a class="expand" href="javascript:;"></a> </div>
      </div>
      <div class="grid-body" style="display:none">
        <div class="scroller" data-height="550px">
	        <table id="visitor_table" cellpadding="0" cellspacing="0" border="0" class="table" width="100%"></table>
	    </div>
      </div>

    </div>
  </div>
  <div class="row-fluid">
    <div class="grid simple" style="overflow:hidden;">
      <div class="span6">
        <div class="grid-title" id="br-title">
          <h4>Browser <span class="semi-bold">Details</span></h4>
          <div class="tools"> <a class="reload MISreload" href="javascript:;" rel="browserData"></a> </div>
        </div>
        <!--tabs-->
        <div class="grid-body pt5">
	    <ul id="browserTab" class="nav nav-tabs new-tab">
      		<li class="active"><a href="#br-table" data-toggle="tab">Table</a></li>
      		<li class=""><a href="#browser-pie" data-toggle="tab">Chart</a></li>
    	</ul>
    	<div id="browserTabContent" class="tab-content" style="height:438px;">
      		<div class="tab-pane active" id="br-table">
               <div class="scroller" data-height="400px">
            <table id="browserTable" cellpadding="0" cellspacing="0" border="0" class="table" width="100%">

            </table>
          </div>
        	</div>
            <div class="tab-pane fade" id="browser-pie" style="width:480px;height: 400px; margin: 0 auto; box-sizing:border-box">
        	</div>
             <span style="float:right">Note : Kindly drilldown only one pie chart at a time</span>
     	</div>
        </div>
        <!-- tabs end-->
      </div>

      <div class="span6">
      <div class="grid simple">
        <div class="grid-title"><h4>Platform <span class="semi-bold"> Details</span></h4><div class="tools"> <a class="reload MISreload" href="javascript:;" rel="platformData"></a> </div></div>
        <div class="grid-body pt5">
		<ul id="deviceTab" class="nav nav-tabs new-tab">
                                    <li class="active"><a href="#device-table" data-toggle="tab">Table</a></li>
                                    <li class=""><a href="#browserShare" data-toggle="tab">Chart</a></li>
                      </ul>
          	<div id="deviceTabContent" class="tab-content">
									<div class="tab-pane active" id="device-table">
                                        <div class="scroller" data-height="400px">
                                            <table id="deviceTable" cellpadding="0" cellspacing="0" border="0" class="table" width="100%">

                                            </table>
                                        </div>
                                    </div>
                                    <div id="browserShare" class="tab-pane fade" style="min-width: 480px; height: 454px; margin: 0 auto">



                                </div>
		</div>
		<div style="text-align:left;display:none;" >
									<a href="javascript:" id="resetChart" >Reset</a>
			</div>
        </div>
      </div>
    </div>
    </div>
  </div>
  <div class="row-fluid">
      <div class="grid simple span8">
        <div class="grid-title">
          <h4>Load Time <span class="semi-bold">Distribution</span></h4>
          <div class="tools"> <a href="javascript:;" class="reload MISreload" rel="loadTimeRanges"></a> </div>
        </div>
        <div class="grid-body ">
          <div id="timeChart" style="height:200px"></div>
        </div>
      </div>
      <div class="span4">
        <div class="grid simple">
          <div class="grid-title">
            <h4>User <span class="semi-bold">Experience</span></h4>
            <div class="tools"> <a class="reload" href="javascript:;" rel="loadTimeRanges"></a></div>
          </div>
          <div class="grid-body">
            <div id="userExp" style="height:200px"></div>
          </div>
        </div>
      </div>
    </div>
  <br/><br/><br>
  <!-- END PAGE -->
</div>
<!-- END PAGE -->
<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?php echo $staticPath;?>/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/j/highcharts_v3.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/j/plugins.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/j/jCommonPlugs.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/j/perfSumCalls.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN CORE TEMPLATE JS -->






<!-- END CORE TEMPLATE JS -->
<!-- END JAVASCRIPTS -->
</body>
