<?php
$label = $l->t('Gigabytes');
$url =\OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->getId(), 'requesttoken' => $_['requesttoken']));
echo '
<h1>';
p($l->t($_['chart']->getConfig()->getChartType()));
echo '</h1>
<div class="chart defaultChart" id="chart" data-url="' . $url . '" data-type="line" data-format="%Y-%m-%d" data-label="' . $label . '"><div class="icon-loading" style="height: 60px;"></div></div>';
