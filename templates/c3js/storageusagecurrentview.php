<h1><?php p($l->t($_['chart']->getConfig()->getChartType())); ?></h1>
<?php
echo '<div class="chart" id="chart"><div class="icon-loading" style="height: 60px;"></div></div>';
?>
<?php
$url = \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->getId(), 'requesttoken' => $_['requesttoken']));
?>
<div style="display: none;" data-url="<?php echo $url; ?>" id="defaultBar"></div>