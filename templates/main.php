<div class="container-fluid">
<?php
$flowLayout = 4;
$imageLoading = \OCP\Util::imagePath('ocusagecharts', 'iconloading.gif');


$chartRows = array_chunk($_['charts'], $flowLayout);

for($i = 0; $i < count($chartRows); $i++)
{
    $chartRow = $chartRows[$i];

    echo '<div class="row-fluid">';

    foreach($chartRow as $chart)
    {
        $config = $chart->getConfig();
        $chartId = $config->chartId;
        // keep it string to lower, because owncloud forces it all over
        $template = strtolower($config->chartProvider .  '/' . $config->chartDataType . 'View');

        echo '<div class="span' . $flowLayout . '">';
            echo '<div id="chart_' . $chartId . '"><img src="' . $imageLoading . '" alt="Loading" title="Loading" /></div>';
            echo $this->inc($template, array('chart' => $chart));
        echo '</div>';
    }

    echo '</div>';
}
?>
</div>