<!DOCTYPE html>
<?php require_once dirname(__FILE__).'/../config/config.php'; ?>
<?php require_once $servicePath.'/lib/authentication/Authenticator.php'; ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>HeatMap Plots</title>
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
    <div class="header-seperation" style="display:none">
      <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
        <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu" class="">
          <div class="iconset top-menu-toggle-white"></div>
          </a> </li>
      </ul>
      <!-- BEGIN LOGO -->
      <a class="dashName" href="#"><img src="<?php echo $staticPath;?>/i/newmonk.png" alt=""/>  </a>
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
      <a class="dashName" href="#" id="logo-center"><img src="<?php echo $staticPath;?>/i/newmonk.png" alt="">  </a>
      <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-right">
        <ul class="nav quick-section">
          <div class="input-prepend inside search-form no-boarder" style="display:none"> <span class="add-on"> <a href="#" class="">

            </a></span>

          </div>
        </ul>
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
    <ul>
      <li class=""> <a href="javascript:;"><i class="icon-rocket"></i><span class="title"> Boomerang</span><!--<span class="arrow "></span>--> </a>
<!--        <ul class="sub-menu">
          <li> <a href="tables.html"><i class="icon-custom-chart"></i> Charts</a> </li>
          <li> <a href="datatables.html"><i class="icon-custom-thumb"></i> Detailed Report</a> </li>
        </ul>-->
      </li>
      <li class=""> <a href="javascript:;" class="redirect"> <i class="icon-bug"></i> <span class="title">nLogger</span></a></li>
      <li class=""> <a href="javascript:;"> <i class="icon-globe"></i><span class="title">Webpage Test API</span> <span class="arrow "></span> </a>
        <ul class="sub-menu">
          <li> <a href="javascript:;"><i class="icon-bar-chart"></i> Analytical Review</a> </li>
        </ul>
      </li>
      <li class=""> <a href="javascript:;"> <i class="li_fire"></i><span class="title">Heatmap</span> </a></li>
    </ul>
    <a href="#" class="scrollup to-edge">Scroll</a>
    <div class="clearfix"></div>
    <!-- END SIDEBAR MENU -->
  </div>
  <!-- END SIDEBAR -->
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content condensed">
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
        <ul class="breadcrumb" id="heat_mapFocus">
          <li>
            <p>YOU ARE HERE</p>
          </li>
          	<i class="icon-angle-right"></i>
            <li><a href="misAppSelect.php" class="appName active">Select App</a> </li>
            <i class="icon-angle-right"></i>
            <li><a href="misDashboard.php" class="active">DashBoard</a> </li>
            <i class="icon-angle-right"></i>
            <li><a href="heatMapUrlList.php" class="urlList active">UrlList</a> </li>
			<i class="icon-angle-right"></i>
		    <li><a href="#" class="active">HeatMap</a></li>
        </ul>
      </div>
      <div class="row-fluid">
        <div class="span12">
          <div class="grid simple" id="urlListGrid">

            <div class="grid-title">
        <div class="row-fluid">
          <h4 class="span6">Heat Map
          <a href="javascript:void(0);" class="heat_btn" id="generate_map" style="float:right">Generate Heat Map</a>
          </h4>
         <!--  <div class="mapBtnCont">
          	<a href="javascript:void(0);" class="heat_btn" id="generate_map">Generate Heat Map</a>
            <a href="javascript:void(0);" class="heat_btn" id="remove_map" style="margin:0 30px 0 5px; visibility:hidden;"  onClick="$('#canvasCont canvas').remove();$(this).css( 'visibility','hidden' )">Remove Heat Map</a>
          	</div>-->
            <a href="#" class="heat_btn remov_btn" id="remove_map" onClick="$('#canvasCont canvas').remove();$(this).css( 'display','none' )">Close</a>
          <div class="span6 pull-right">
                  <div class="tools pull-right"> <a class="reload MISreload" href="javascript:;" rel="heatMapData"></a> </div>
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
                <div class="grid-body pageCont">
                    <iframe class="url_iframe" src="" id="urlCont" scrolling="no"></iframe>
                    <div id="canvasCont" class="heatmap_plot hideCanvas"></div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="admin-bar" id="quick-access" style="display:">
      <div class="admin-bar-inner">
        <div class="form-horizontal">
          <select id="val1" class="select2-wrapper">
            <option value="Gecko"> Gecko </option>
            <option value="Webkit"> Webkit </option>
            <option value="KHTML"> KHTML </option>
            <option value="Tasman"> Naukri - Recruiter Profile </option>
          </select>
          <select id="val1" class="select2-wrapper">
            <option value="Gecko"> Gecko </option>
            <option value="Webkit" > Webkit </option>
            <option value="KHTML"> Internet Explorer 6 </option>
            <option value="Tasman"> Tasman </option>
          </select>
          <select id="val2" class="select2-wrapper">
            <option value="Internet Explorer 10"> Internet Explorer 10 </option>
            <option value="Firefox 4.0"> Win 98+ </option>
            <option value="Chrome"> Chrome </option>
          </select>
        </div>
        <button class="btn btn-primary btn-cons btn-add" type="button">Search </button>
        <button class="btn btn-white btn-cons btn-cancel" type="button">Cancel</button>
      </div>
    </div>
    <div class="addNewRow"></div>
  </div>
</div>
<!-- END PAGE -->
<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?php echo $staticPath;?>/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>

<script src="<?php echo $staticPath;?>/j/plugins.min.js" type="text/javascript"></script>





<script src="<?php echo $staticPath;?>/js/form_elements_v6.js" type="text/javascript"></script>
<!-- BEGIN CORE TEMPLATE JS -->
<script src="<?php echo $staticPath;?>/js/core_v2.js" type="text/javascript"></script>
<!-- END CORE TEMPLATE JS -->
<script src="<?php echo $staticPath;?>/js/getjson_v1.js"></script>
<script src="<?php echo $staticPath;?>/js/utility_v2.js"></script>
<script src="<?php echo $staticPath;?>/js/breadcrumb_v3.js"></script>
<!-- toolbar_v2 is deprecated use v3 -->
<script src="<?php echo $staticPath;?>/js/init_toolbars_v2.js"></script>
<!-- Heatmap Javascript -->
<script type="text/javascript" src="<?php echo $staticPath;?>/js/heatmap.js"></script>
<script type="text/javascript" src="<?php echo $staticPath;?>/js/plot_heatmap_v3.js"></script>



<!-- END JAVASCRIPTS -->
</body>



