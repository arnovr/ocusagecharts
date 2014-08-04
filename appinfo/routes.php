<?php
namespace OCA\ocUsageCharts\AppInfo;

$application = new Chart();
$application->registerRoutes($this, array(
    'routes' => array(
        array('name' => 'charts#show', 'url' => '/', 'verb' => 'GET'),
    )
));