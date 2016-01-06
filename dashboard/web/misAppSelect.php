<!DOCTYPE html>
<?php require_once dirname(__FILE__).'/../config/config.php'; ?>
<?php require_once $servicePath.'/lib/authentication/Authenticator.php'; ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8" />
    <title>NewMonk AppSelect</title>
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
    <!-- END CSS TEMPLATE -->
<script>
	var serviceBaseUrl = "<?php echo $serviceBaseUrl;?>";
    var errorBaseUrl = "<?php echo $errorBaseUrl;?>";
</script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">

    <div class="page-content loginCont">
        <br/>
        <br/>
        <br/>
        <div class="appId">
        <form name="appSelect" method="get" action="misDashboard.php">
            <input type="hidden" id="appName" name="appName" />
            <div class="grid simple" >
                <div class="grid-title no-border">
              <h4>Select <span class="semi-bold">appID</span></h4>
            </div>
                <div class="grid-body no-border">
                        <div class="row-fluid">
                            <div class="span12">
	                            <select name="domain" id="domain" style="width:100%">

                                </select>
                                <select name="appId" id="source" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="input-append date span12">
                                    <input id="startDate" name="startDate" type="text" class="span12" id="sandbox-advance" />
                                    <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span> </div>
                            </div>
                            <div class="span6">
                                <div class="input-append date span12">
                                    <input id="endDate" name="endDate" type="text" class="span12" id="sandbox-advance" />
                                    <span class="add-on"><span class="arrow"></span><i class="icon-th"></i></span> </div>
                            </div>
                        </div>
                    <div class="form-actions">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary btn-cons"> Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </form>
    </div>

    <!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?php echo $staticPath;?>/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/j/plugins.min.js" type="text/javascript"></script>
<script src="<?php echo $staticPath;?>/j/jCommonPlugs.min.js"></script>
<!-- END JAVASCRIPTS -->
</body>
