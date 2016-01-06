<!DOCTYPE html>
<?php require_once dirname(__FILE__).'/../config/config.php'; ?>
<?php require_once $servicePath.'/lib/authentication/Authenticator.php'; ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>NewMonk DashBoard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN PLUGIN CSS -->
<!-- END PLUGIN CSS -->
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?php echo $staticPath;?>/c/core.min.css" rel="stylesheet" type="text/css" />
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?php echo $staticPath;?>/c/brand.min.css" rel="stylesheet" type="text/css" />
<script>
	var serviceBaseUrl = "<?php echo $serviceBaseUrl;?>";
  var errorBaseUrl = "<?php echo $errorBaseUrl;?>";
</script>
<!-- END CSS TEMPLATE -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">
<div class="page-content loginCont">
  <br/>
  <br/>
  <div class="login"><a href="login.php" class="dashName"><img alt="" src="<?php echo $staticPath;?>/i/newmonk.png"> </a></div>
  <div class="content MIS">
    <div class="row-fluid ">
      <div class="span4 single-colored-widget">
        <div class="content-wrapper MIStile blue">
        <h3 class="semi-bold">Performance</h3>
        <div class="iconWrap"><i class="icon-rocket"></i></div>
        <h5 class="semi-bold"><a href="boomDashboardUrls.php" rel="0">Access Performance&nbsp;&nbsp;<i class="icon-external-link"></i></a></h5>
          <div class="clearfix"></div>
        </div>
        <div class="heading">
          <p>Measure the performance of your website from your end user's point of view. With boomerang, you find out exactly how fast your users think your site is.</p>
          <ul>
            <li>Tag similiar URLs <span style="color:#F00">*</span></li>
            <li>Check Average Load Time and Page Views</li>
            <li>Time for DNS lookup, FirstByte, PageLoad </li>
            <li>Distribute Pageview OS and Browser wise</li>
          </ul>
          </ul>
          <div class="clearfix"> </div>
        </div>
      </div>
      <div class="span4 single-colored-widget">
        <div class="content-wrapper MIStile red">
        <h3 class="semi-bold">Errors</h3>
        <div class="iconWrap"><i class="icon-bug"></i></div>
        <h5 class="semi-bold"><a href="#" rel="1" class="kibLink" target="_blank">Access Errors&nbsp;&nbsp;<i class="icon-external-link"></i></a></h5>
          <div class="clearfix"></div>
        </div>
        <div class="heading">
         <p>Checkout the JavaScript errors on the client side and the error details.</p>
          <ul>
            <li>Error Url listing specific to your App</li>
            <li>Check error type and its count</li>
            <li>Filter your results based on OS, Screen Resoultion and Browser</li>
            <li>View files where the error has occured</li>
            <br />
            <br />
            <br />
          </ul>
          <div class="clearfix"> </div>
        </div>
      </div>
      <!--div class="span3 single-colored-widget">
        <div class="content-wrapper MIStile green">
        <h3 class="semi-bold">Webpage Test</h3>
        <div class="iconWrap"><i class="icon-globe"></i></div>
        <h5 class="semi-bold"><a href="#" rel="2">Access Webpage Test&nbsp;&nbsp;<i class="icon-external-link"></i></a></h5>
          <div class="clearfix"></div>
        </div>
        <div class="heading">
           <p>A package of truly animated vector icons, along with a customizer for reducing payload.</p>
          <ul>
            <li>Lorem ipsum dolor sit amet</li>
            <li>Consectetur adipiscing elit</li>
            <li>Integer molestie lorem at massa</li>
            <li>Facilisis in pretium nisl aliquet</li>
            <li>Nulla volutpat aliquam velit</li>
            <li>Faucibus porta lacus fringilla vel</li>
            <li>Aenean sit amet erat nunc</li>
            <li>Eget porttitor lorem</li>
            <br />
          </ul>
          <div class="clearfix"> </div>
        </div>
      </div-->
       <div class="span4 single-colored-widget">
        <div class="content-wrapper MIStile heat">
        <h3 class="semi-bold">HeatMap</h3>
        <div class="iconWrap"><i class="li_fire"></i></div>
        <h5 class="semi-bold"><a href="heatMapUrlList.php" rel="3">Access HeatMap&nbsp;&nbsp;<i class="icon-external-link"></i></a></h5>
          <div class="clearfix"></div>
        </div>
        <div class="heading">
          <p>A package of truly animated vector icons, along with a customizer for reducing payload.</p>
          <ul>
            <li>Lorem ipsum dolor sit amet</li>
            <li>Consectetur adipiscing elit</li>
            <li>Integer molestie lorem at massa</li>
            <li>Facilisis in pretium nisl aliquet</li>
            <li>Nulla volutpat aliquam velit</li>
            <li>Faucibus porta lacus fringilla vel</li>
            <li>Aenean sit amet erat nunc</li>
            <li>Eget porttitor lorem</li>
            <br />
          </ul>
          <div class="clearfix"> </div>
        </div>
      </div>
      <!--div class="span3 single-colored-widget" style="margin:25px 0 0 0">
        <div class="content-wrapper MIStile green">
        <h3 class="semi-bold">TNM</h3>
        <div class="iconWrap"><i class="icon-globe"></i></div>
        <h5 class="semi-bold"><a href="tnmDashboardUrls.php" rel="2">Time & Motion&nbsp;&nbsp;<i class="icon-external-link"></i></a></h5>
          <div class="clearfix"></div>
        </div>
      </div-->
    </div>
  </div>
</div>
</div></body>
<script src="<?php echo $staticPath;?>/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/j/plugins.min.js"></script>
<script src="<?php echo $staticPath;?>/j/jCommonPlugs.min.js"></script>
<script>
var widgets = $('.single-colored-widget');
widgets.each(function(a,b){
	$(b).css({'cursor':'pointer'})
	$(b).click(function(){
			$(this).find('a')[0].click();
	});
})
$('h5.semi-bold a').each(function(index,anchor){
   if($(anchor).hasClass('kibLink')){
    anchor.href = "<?php echo $errorBaseUrl;?>"+"/errors/report/#/dashboard/NewMonk-Error:-Browser?_g=(time:(from:now-7d,mode:quick,to:now))&_a=(query:(query_string:(analyze_wildcard:!t,lowercase_expanded_terms:!f,query:'appId:"+ Utility.getUrlParam('appId') +"')))";
  }
  else{
    anchor.href+= Utility.updateQueryString({sideBarIndex:index});
  }
});
</script>
