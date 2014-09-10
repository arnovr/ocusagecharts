<?php
$url = \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->getId(), 'requesttoken' => $_['requesttoken']));
echo '
<div id="controls" style="margin-left: 3px;">
    <div id="new" class="button">
        <a
            data-url="' . $url . '"
            data-size="kb"
            data-type="line"
            data-format="%Y-%m-%d"
            data-label="';
p($l->t('Kilobytes'));
echo '" href="#"
            >';
p($l->t('Kilobytes'));
echo '</a>
    </div>

    <div id="new" class="button">
        <a
            id="defaultChart"
            data-url="' . $url . '"
            data-size="mb"
            data-type="line"
            data-format="%Y-%m-%d"
            data-label="';
p($l->t('Megabytes'));
echo '" href="#"
            >';
p($l->t('Megabytes'));
echo '</a>
    </div>

    <div id="new" class="button">
        <a
            data-url="' . $url . '"
            data-size="gb"
            data-type="line"
            data-format="%Y-%m-%d"
            data-label="';
p($l->t('Gigabytes'));
echo '" href="#"
            >';
p($l->t('Gigabytes'));
echo '</a>
    </div>
</div>

<div style="height: 50px;"></div>
<h1>';
p($l->t($_['chart']->getConfig()->getChartType()));
echo '</h1>
<div class="chart" id="chart"><div class="icon-loading" style="height: 60px;"></div></div>';
