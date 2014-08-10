<div id="chart_<?php p($_['chart']->getId()); ?>">Stub</div>
<script type="application/javascript">
    var chart<?php echo $_['chart']->getId();?> = c3.generate({
        bindto: '#chart_<?php echo $_['chart']->getId();?>',
        data: {
            url: '<?php echo \OCP\Util::linkToRoute('ocUsageCharts.chart.load_chart', array('id' => $_['chart']->getId()));?>',
            mimeType: 'json',
            type : '<?php echo $_['chart']->getChartType(); ?>'
        }
    });
</script>