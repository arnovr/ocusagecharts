<?php

use OCA\ocUsageCharts\AppInfo\Chart;

$app = new Chart();
$container = $app->getContainer();
$user = $container->query('OwncloudUser');
$appConfig = $container->query('ServerContainer')->getConfig();

$url = $appConfig->getAppValue('ocusagecharts', 'dashboard_url');

if ( empty($url) )
{
    $url = $appConfig->getAppValue('ocusagecharts', 'dashboard_url') . ':5601/#/dashboard';
}

echo '
 <div style="width:100%; height:100%; background-color:transparent;position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px;">
    <iframe src="' . $url . '" width="100%" height="100%" frameborder="0" style="position:absolute;width: 100%; height: 100%; border: 0px;"></iframe>
  </div>';