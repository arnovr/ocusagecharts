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
$imageLoading = \OCP\Util::imagePath('ocusagecharts', 'iconloading.gif');

foreach($_['charts'] as $chart)
{
    echo '<h1>Chart</h1>';
    $config = $chart->getConfig();
    $chartId = $config->chartId;
    // keep it string to lower, because owncloud forces it all over
    $template = strtolower($config->chartProvider .  '/' . $config->chartDataType . 'View');

    echo '<div>';
        //echo '<div id="chart_' . $chartId . '"><img src="' . $imageLoading . '" alt="Loading" title="Loading" /></div>';
        echo '<div id="chart_' . $chartId . '"><div class="icon-loading" style="height: 60px;"></div></div>';
        echo $this->inc($template, array('chart' => $chart));
    echo '</div>';
}
?>
        </div>
</div>