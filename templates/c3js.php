<div id="chart_<?php p($_['chart']->getId()); ?>"></div>
<script type="application/javascript">
    <?php
    if ( $_['chart']->getChartType() != 'pie' ) {
    ?>
    var chart<?php echo $_['chart']->getId();?> = c3.generate({
        bindto: '#chart_<?php echo $_['chart']->getId();?>',
        data: {
            x: 'x',
            x_format: '%Y-%m-%d',
            mimeType: 'json',
            url: '<?php echo \OCP\Util::linkToRoute('ocUsageCharts.chart.load_chart', array('id' => $_['chart']->getId()));?>'
        }
    });

    <?php
        }
        else
        {
        ?>
    var chart<?php echo $_['chart']->getId();?> = c3.generate({
        bindto: '#chart_<?php echo $_['chart']->getId();?>',
        data: {
            url: '<?php echo \OCP\Util::linkToRoute('ocUsageCharts.chart.load_chart', array('id' => $_['chart']->getId()));?>',
            mimeType: 'json',
            type : '<?php echo $_['chart']->getChartType(); ?>'
        }
    });
    <?php

        }
    ?>
</script>