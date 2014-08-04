<?php
namespace OCA\ocUsageCharts\AppInfo;

$application = new Chart();
$application->registerRoutes($this, array(
    'routes' => array(
        array('name' => 'chart#show', 'url' => '/', 'verb' => 'GET'),
    )
));