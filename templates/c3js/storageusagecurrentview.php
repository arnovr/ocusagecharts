<?php
$url = \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->getId(), 'requesttoken' => $_['requesttoken']));
echo '<h1>';
p($l->t($_['chart']->getConfig()->getChartType()));
echo '</h1>';
echo '<div class="chart" id="chart"><div class="icon-loading" style="height: 60px;"></div></div>';
echo '<div style="display: none;" data-url="' . $url . '" class="defaultPie"></div>';
