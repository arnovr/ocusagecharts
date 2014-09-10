<?php
echo '<h1>';
p($l->t($_['chart']->getConfig()->getChartType()));
echo '</h1>';
echo '<div class="chart" id="chart"><div class="icon-loading" style="height: 60px;"></div></div>';
$url = \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->getId(), 'requesttoken' => $_['requesttoken']));
echo '<div style="display: none;" data-url="' . $url . '" id="defaultBar"></div>';