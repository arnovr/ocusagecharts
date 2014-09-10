<?php
echo '<div id="app">
    <div id="app-navigation">
        <ul>';

foreach($_['configs'] as $config)
{
    $url = \OCP\Util::linkToRoute('ocusagecharts.chart.display_chart', array('id' => $config->getId()));
    echo '<li><a href="' . $url . '">' . $l->t($config->getChartType()) . '</a></li>';
}
echo '
        </ul>
    </div>
    <div id="app-content">';

$requesttoken = $_['requesttoken'];
$chart = $_['chart'];
$config = $chart->getConfig();

// keep it string to lower, because owncloud forces it
$template = strtolower($config->getChartProvider() .  '/' . $config->getChartType() . 'View');
echo $this->inc($template, array('chart' => $chart, 'requesttoken' => $requesttoken));

echo '
        </div>
</div>';
