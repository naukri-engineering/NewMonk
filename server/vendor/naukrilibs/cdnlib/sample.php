<?php
include("CDNHeaders.class.php");

echo "Remote IP Address : ".CDNHeaders::getInstance()->getRemoteIP()."\n";

?>
