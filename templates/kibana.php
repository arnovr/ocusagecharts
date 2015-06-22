<?php

use OCA\ocUsageCharts\AppInfo\Chart;

$app = new Chart();
$container = $app->getContainer();
$user = $container->query('OwncloudUser');
$appConfig = $container->query('ServerContainer')->getConfig();

echo '
 <div style="width:100%; height:100%; background-color:transparent;position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px;">
    <iframe src="' . $appConfig->getAppValue('ocusagecharts', 'url') . ':5601" width="100%" height="100%" frameborder="0" style="position:absolute;width: 100%; height: 100%; border: 0px;"></iframe>
  </div>';