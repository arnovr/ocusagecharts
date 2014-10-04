<?php
echo '<div id="app">
    <div id="app-navigation">
<ul>    ';

$chartTypes = array(
    'Storage',
    'Activity'
);
foreach($chartTypes as $possibleType)
{
    echo '<li class="menu-title"><h2>' . $l->t($possibleType . '_title') . '</h2></li>';

    foreach($_['configs'] as $config)
    {
        if ( substr($possibleType, 0, 7) !== substr($config->getChartType(), 0, 7) )
        {
            continue;
        }
        $url = \OCP\Util::linkToRoute('ocusagecharts.chart.display_chart', array('id' => $config->getId()));
        echo '<li><a href="' . $url . '">' . $l->t($config->getChartType()) . '</a></li>';
    }
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
