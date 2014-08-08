<?php
foreach($_['charts'] as $chart)
{
    echo $this->inc($chart->getTemplateName(), array('chart' => $chart));
}
