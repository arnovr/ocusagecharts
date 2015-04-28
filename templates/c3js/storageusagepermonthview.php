<?php
use OCA\ocUsageCharts\Owncloud\TemplateHelpers\ChartViewHelper;
use OCA\ocUsageCharts\Owncloud\TemplateHelpers\TemplateDto;
use OCA\ocUsageCharts\Owncloud\TemplateHelpers\TemplateParser;

$chartConfig = $_['chart']->getConfig();

$templateDto = new TemplateDto(
    $this->inc(strtolower($chartConfig->getChartProvider()) . '/template'),
    $_['requesttoken'],
    '%Y-%m',
    'bar',
    'defaultBar');

$templateParser = new TemplateParser(
    new ChartViewHelper($chartConfig),
    $templateDto,
    $l
);
echo $templateParser->getTemplate();
