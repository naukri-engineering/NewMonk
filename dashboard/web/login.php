<!DOCTYPE html>
<?php require_once dirname(__FILE__).'/../config/config.php'; ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>NewMonk Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- BEGIN PLUGIN CSS -->
<!-- END PLUGIN CSS -->
<!-- BEGIN CORE CSS FRAMEWORK -->
 <link href="<?php echo $staticPath;?>/c/core.css" rel="stylesheet" type="text/css" />
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?php echo $staticPath;?>/c/brand.css" rel="stylesheet" type="text/css" />
<!-- END CSS TEMPLATE -->
<script>
  var baseUrl = "<?php echo $baseUrl;?>";
	var serviceBaseUrl = "<?php echo $serviceBaseUrl;?>";
  var prefetchAssets = ['<?php echo $staticPath;?>/plugins/jquery-1.8.3.min.js', '<?php echo $staticPath;?>/j/plugins.min.js'];
</script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">
<form name="login" method="post">
<div class="page-content loginCont">
<br/>
<br/>
<br/>
  <div class="login wLarge"><a href="#" class="dashName"><img alt="" src="<?php echo $staticPath;?>/img/newmonk.png"></a>
    <div class="grid simple">
      <div class="grid-body no-border">
        <div class="row-fluid">
          <h3>Login</h3>
          <div class="row-fluid">
            <div class="input-append span12 primary">
              <input type="text" id="appendedInput" class="span10" placeholder="someone@example.com" name="username" />
              <span class="add-on"><span class="arrow"></span><i class="icon-align-justify"></i> </span> </div>
          </div>
          <div class="row-fluid">
            <div class="input-append span12 primary">
              <input type="password" id="appendedInput2" class="span10" placeholder="your password" name="password" />
              <span class="add-on"><span class="arrow"></span><i class="icon-lock"></i> </span> </div>
          </div>
        </div>
        <div class="form-actions">
          <div class="pull-right">
          	<input type="hidden" name="url" />
            <button type="submit" class="btn btn-primary btn-cons"> Login</button>
            <button type="button" class="btn btn-white btn-cons" style="display:none;">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
<script>
document.forms.login.action = serviceBaseUrl+'/authenticate.php';
document.forms.login.elements.url.value = baseUrl+'/misAppSelect.php';
</script>
<script>
  window.onload = function(){
    if(typeof(prefetchAssets)!='undefined'){
      var link;

      for(var i=0; i<prefetchAssets.length; i++){
        link = document.createElement("link");
        link.setAttribute("rel", "prerender prefetch")
        link.setAttribute("href", prefetchAssets[i]);
        document.head.appendChild(link);
      }
    
    }
}
</script>

</body>
