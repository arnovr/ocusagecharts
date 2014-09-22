<?php
use OCA\ocUsageCharts\Entity\ChartConfig;

$label = $l->t('sizes_gb');

/** @var ChartConfig $chartConfig */
$chartConfig = $_['chart']->getConfig();
$meta = json_decode($chartConfig->getMetaData());
if ( !empty($meta) )
{
    $label = $l->t('sizes_' . $meta->size);
}

$url =\OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $chartConfig->getId(), 'requesttoken' => $_['requesttoken']));
echo '
<h1>';
p($l->t($chartConfig->getChartType()));
echo '</h1>
<div class="chart defaultChart" id="chart" data-url="' . $url . '" data-type="bar" data-format="%Y-%m" data-label="' . $label . '"><div class="icon-loading" style="height: 60px;"></div></div>';
