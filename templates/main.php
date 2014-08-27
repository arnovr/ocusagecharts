<div id="app">
    <div id="app-navigation">
        <ul>
            <li><a href="<?php echo \OCP\Util::linkToRoute('ocusagecharts.chart.display_chart', array('id' => 1)); ?>">Free space / Used space</a></li>
            <li><a href="<?php echo \OCP\Util::linkToRoute('ocusagecharts.chart.display_chart', array('id' => 2)); ?>">Daily Usage</a></li>
            <li><a href="<?php echo \OCP\Util::linkToRoute('ocusagecharts.chart.display_chart', array('id' => 3)); ?>">Weekly Usage</a></li>
            <li><a href="<?php echo \OCP\Util::linkToRoute('ocusagecharts.chart.display_chart', array('id' => 4)); ?>">Monthly Usage</a></li>

        </ul>
    </div>
    <div id="app-content">
<?php
$requesttoken = $_['requesttoken'];
foreach($_['charts'] as $chart)
{
    $config = $chart->getConfig();
    $chartId = $config->chartId;
    // keep it string to lower, because owncloud forces it all over
    $template = strtolower($config->chartProvider .  '/' . $config->chartDataType . 'View');

        echo $this->inc($template, array('chart' => $chart, 'requesttoken' => $requesttoken));
}
?>
        </div>
</div>