<?php
namespace OCA\ocUsageCharts\AppInfo;

$application = new Chart();
$application->registerRoutes($this, array(
    'routes' => array(
        array('name' => 'chart#index', 'url' => '/', 'verb' => 'GET'),
        array('name' => 'chart#show', 'url' => '/show', 'verb' => 'GET'),
    )
));