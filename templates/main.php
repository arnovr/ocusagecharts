<?php
foreach($_['charts'] as $chart)
{
    $template = $chart->getConfig()->chartProvider .  '/' . $chart->getConfig()->chartDataType . 'View';
    echo $this->inc($template, array('chart' => $chart));
}
