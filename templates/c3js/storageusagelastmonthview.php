<?php
use OCA\ocUsageCharts\Owncloud\TemplateHelpers\ChartViewHelper;

$chartConfig = $_['chart']->getConfig();
$chartViewHelper = new ChartViewHelper($chartConfig);

$label = $chartViewHelper->getLabel($l);
$shortLabel = $chartViewHelper->getShortLabel();
$url = $chartViewHelper->getUrl($_['requesttoken']);
$title = $chartViewHelper->getTitle($l);

$chartType = 'defaultChart';

$template = '
<h1>[title]</h1>
<div
    class="chart [charttype]"
    id="chart"
    data-url="[url]"
    data-type="line"
    data-format="%Y-%m-%d"
    data-label="[label]"
    data-shortlabel="[shortlabel]"
>
<div class="icon-loading" style="height: 60px;"></div>
</div>';

